<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Enums\NombreRole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
/**
     * Controlador de Usuarios
     *
     * Gestiona el registro, edición, listado, filtrado y eliminación
     * de usuarios. También métodos para manejar el
     * perfil propio del usuario y detectar clientes "fantasma".
     */
class UserController extends Controller
{    
    /**
     * Lista usuarios paginados con sus roles.
     *
     * @return \Illuminate\View\View
     */
    public function listarUsuarios()
    {
        $usuarios = User::with('role')->paginate(15); // Eager loading de la relación 'role'cargando los roles asociados a los usuarios de antemano
        $mensaje = $usuarios->total() > 0 ? "Usuarios encontrados: {$usuarios->total()}" : "No se han encontrado usuarios.";
        return view('parciales.listados.listausuarios', compact('usuarios', 'mensaje'));
    }

    /**
     * Filtra usuarios según los parámetros de la petición.
     *
     * Filtra por parámetros y devuelve una
     * lista paginada según los filtros aplicados.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function filtrarUsuarios(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (aceptando GET y POST)
        $usuarios = User::with('role')
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('telefono'), function ($consulta) use ($peticion) {
                return $consulta->where('telefono', 'like', '%' . $peticion->telefono . '%');
            })
            ->when($peticion->filled('email'), function ($consulta) use ($peticion) {
                return $consulta->where('email', 'like', '%' . $peticion->email . '%');
            })
            ->when($peticion->filled('nombre'), function ($consulta) use ($peticion) {
                return $consulta->where('nombre', 'like', '%' . $peticion->nombre . '%');
            })
            ->when($peticion->filled('apellidos'), function ($consulta) use ($peticion) {
                return $consulta->where('apellidos', 'like', '%' . $peticion->apellidos . '%');
            })
            ->when($peticion->filled('dni'), function ($consulta) use ($peticion) {
                return $consulta->where('dni', 'like', '%' . $peticion->dni . '%');
            })
            ->when($peticion->filled('role'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('role', function ($consultar) use ($peticion) {
                    $consultar->where('nombre_role', $peticion->role);
                });
            })
            ->when($peticion->has('marcado_eliminar'), function ($consulta) use ($peticion) {
                return $consulta->where('marcado_eliminar', true);
            })
            ->paginate(15)
            ->appends($peticion->except('page'));

        $mensaje = $usuarios->total() > 0 ? "Usuarios encontrados: {$usuarios->total()}" : "No se han encontrado usuarios.";
        return view('parciales.listados.listausuarios', compact('usuarios', 'mensaje'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     *
     * Carga el usuario por ID y los roles disponibles desde el enum.
     *
     * @param int $id ID del usuario
     * @return \Illuminate\View\View
     */
    public function mostrarFormEditarUser($id)
    {
        $usuario = User::findOrFail($id);
        // Usar enums nativos para valores de rol
        $roles = NombreRole::values();

        return view('administracion.formularios.formeditaruser', [
            'usuario' => $usuario,
            'roles' => $roles
        ]);
    }

    /**
     * Elimina un usuario por su ID.
     *
     * Devuelve una vista de éxito o error según el resultado.
     *
     * @param int $id ID del usuario a eliminar
     * @return \Illuminate\View\View
     */
    public function borrarUsuario($id)
    {
        // Buscar el usuario por ID y si existe, eliminarlo
        $usuario = User::find($id);
        if ($usuario) {
            $eliminado = $usuario->delete();
            if ($eliminado) {
                return view('errores.exito', ['mensaje' => "Usuario con ID {$id} y email {$usuario->email} eliminado con exito"]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el usuario {$id}"]);
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     *
     * Proporciona la lista de roles y si el usuario autenticado tiene
     * privilegios avanzados para determinadas operaciones.
     *
     * @return \Illuminate\View\View
     */
    public function mostrarFormNuevoUser()
    {
        $roles = NombreRole::values();
    
        $tienePrivilegios = false;
        if (Auth::check() && Auth::user()) {
            /** @var \App\Models\User $user */ // Aseguramos que $user es una instancia de User para acceder a sus métodos
            $user = Auth::user();
            $tienePrivilegios = $user->privilegios()->where('nombre_priv', 'admin_avanzado')->exists();
        }

        return view('administracion.formularios.formnuevouser', [
            'roles' => $roles,
            'tienePrivilegios' => $tienePrivilegios
        ]);
    }

    /**
     * Registra un nuevo usuario.
     *
     * Valida la petición, crea el usuario (hasheando la contraseña)
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function registrarNuevoUser(Request $peticion)
    {
        // 1. Validar con password_confirmation y regex para seguridad de contraseña.
        $datosValidados = $peticion->validate([
            'nombre' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'apellidos' => 'required|string|max:50',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:250',
            'dni' => ['required', 'string', 'max:10', 'unique:users,dni', 'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J])$/i'], // Validación de DNI, NIE o NIF con regex.
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'], // Al menos 8 caracteres, una mayúscula, una minúscula y un número
            'role' => ['nullable','string', Rule::in(NombreRole::values())]
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La contraseña y la confirmación no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.',
            'dni.regex' => 'Introduzca un DNI, NIE o NIF válido.',
            'unique' => 'El :attribute ya está en uso.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.'
        ]);

        // 2. Crear usuario usando el método create o asignación manual segura
        $usuario = new User();
        $usuario->fill([
            'nombre' => $datosValidados['nombre'],
            'email' => $datosValidados['email'],
            'apellidos' => $datosValidados['apellidos'],
            'telefono' => $datosValidados['telefono'],
            'direccion' => $datosValidados['direccion'],
            'dni' => $datosValidados['dni'],
        ]);

        // Uso Hash::make para la encriptación de la contraseña
        $usuario->password = \Illuminate\Support\Facades\Hash::make($peticion->password);

        // Manejo del Rol, si no se proporciona, asignamos el rol CLIENTE por defecto
        $nombreRole = $peticion->input('role', NombreRole::CLIENTE->value);
        $rol = Role::where('nombre_role', $nombreRole)->first();
        if ($rol) {
            $usuario->role_id = $rol->id;
        }

        // Guardar el usuario en la BD y devolver la vista que corresponda
        $guardado = $usuario->save();

        if ($guardado) {
            return view('errores.exito', ['mensaje' => "Usuario registrado con éxito."]);
        } else {
            return view('errores.error', ['mensaje' => 'Error en el registro del usuario.']);
        }
    }

    /**
     * Procesa la edición de un usuario existente.
     *
     * Valida la entrada y actualiza únicamente los campos proporcionados,
     * incluyendo cambio de contraseña y rol.
     *
     * @param \Illuminate\Http\Request $peticion
     * @param int $id ID del usuario
     * @return \Illuminate\View\View
     */
    public function procesarFormEditarUsuario(Request $peticion, $id)
    {
        $usuario = User::findOrFail($id);

        // Validar los datos recibidos
        $datosValidados = $peticion->validate([
            'nombre' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $id, 
            'apellidos' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:250',
            'dni' => ['nullable', 'string', 'max:10', 'unique:users,dni,' . $id, 'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J])$/i'], // Validación de DNI, NIE o NIF con regex.
            //'dni' => 'nullable|string|max:50|unique:users,dni,' . $id,
            'password' => ['nullable', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'],
            'role' => ['nullable','string', Rule::in(NombreRole::values())]
        ], [
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está en uso por otro usuario.',
            'dni.unique' => 'El DNI ya está en uso por otro usuario.',
            'dni.regex' => 'Introduzca un DNI, NIE o NIF válido.',
            'password.confirmed' => 'La contraseña y la confirmación no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'string' => 'El campo :attribute debe ser texto.'
        ]);

        
            // Actualizar solo los campos que tienen datos
            if ($peticion->filled('nombre')) {
                $usuario->nombre = $datosValidados['nombre'];
            }
            if ($peticion->filled('email')) {
                $usuario->email = $datosValidados['email'];
            }
            if ($peticion->filled('apellidos')) {
                $usuario->apellidos = $datosValidados['apellidos'];
            }
            if ($peticion->filled('telefono')) {
                $usuario->telefono = $datosValidados['telefono'];
            }
            if ($peticion->filled('direccion')) {
                $usuario->direccion = $datosValidados['direccion'];
            }
            if ($peticion->filled('dni')) {
                $usuario->dni = $datosValidados['dni'];
            }

            // Si se proporcionó una contraseña nueva, actualizarla y hashearla.
            if ($peticion->filled('password')) {
                $usuario->password = \Illuminate\Support\Facades\Hash::make($peticion->password);
            }

            if ($peticion->filled('role')) {
                $rol = Role::where('nombre_role', $peticion->role)->first();
                if ($rol) {
                    $usuario->role_id = $rol->id;
                }
            }

            // Guardar los cambios y devolver la vista que corresponda
            $guardado = $usuario->save();

            if ($guardado) {
                return view('errores.exito', ['mensaje' => "Usuario con ID {$id} correctamente modificado"]);
            } else {
                return view('errores.error', ['mensaje' => 'Error en la modificación de datos']);
            }       
    }

    /**
     * Muestra el formulario para editar el perfil
     * propio del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */

    public function mostrarFormEditarMiPerfil()
    {
        $usuario = Auth::check() ? User::find(Auth::id()) : null;

        if (!$usuario) {
            return view('errores.error', ['mensaje' => 'Usuario no encontrado o no autenticado.']);
        }

        return view('administracion.formularios.formeditarmiperfil', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Procesa la edición del propio perfil del usuario autenticado.
     *
     * Requiere la verificación de la contraseña previa.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function procesarFormEditarMiPerfil(Request $peticion)
    {
        // Obtener la instancia del usuario autenticado
        $usuario = Auth::check() ? User::find(Auth::id()) : null;

        // Si no se encuentra el usuario, devolver error
        if (! $usuario) {
            return view('errores.error', ['mensaje' => 'Usuario no encontrado o no autenticado.']);
        }

        // Primero, verificar que la contraseña previa es correcta
        $peticion->validate([
            'password_previa' => 'required',
        ], [
            'password_previa.required' => 'Debe introducir su contraseña actual para modificar sus datos.',
        ]);

        // Comprobar que la contraseña previa coincide con la del usuario autenticado
        if (!\Illuminate\Support\Facades\Hash::check($peticion->password_previa, $usuario->password)) {
            return back()->withErrors(['password_previa' => 'La contraseña actual es incorrecta.'])->withInput();
        }

        // Validar los datos del formulario
        $datosValidados = $peticion->validate([
            'nombre' => 'nullable|string|max:50',
            'apellidos' => 'nullable|string|max:50',
            'dni' => ['nullable', 'string', 'max:10', 'unique:users,dni,' . $usuario->id, 'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J])$/i'],
            'direccion' => 'nullable|string|max:250',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'],
        ], [
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está en uso por otro usuario.',
            'dni.unique' => 'El DNI ya está en uso por otro usuario.',
            'dni.regex' => 'Introduzca un DNI, NIE o NIF válido.',
            'password.confirmed' => 'La nueva contraseña y la confirmación no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'string' => 'El campo :attribute debe ser texto.'
        ]);

       
            // Actualizar los campos proporcionados
            if ($peticion->filled('nombre')) {
                $usuario->nombre = $datosValidados['nombre'];
            }
            if ($peticion->filled('apellidos')) {
                $usuario->apellidos = $datosValidados['apellidos'];
            }
            if ($peticion->filled('dni')) {
                $usuario->dni = $datosValidados['dni'];
            }
            if ($peticion->filled('direccion')) {
                $usuario->direccion = $datosValidados['direccion'];
            }
            if ($peticion->filled('telefono')) {
                $usuario->telefono = $datosValidados['telefono'];
            }
            if ($peticion->filled('email')) {
                $usuario->email = $datosValidados['email'];
            }

            // Si se proporcionó una nueva contraseña, actualizarla
            if ($peticion->filled('password')) {
                $usuario->password = \Illuminate\Support\Facades\Hash::make($peticion->password);
            }

            // Guardar los cambios en la base de datos
            $guardado = $usuario->save();

            if ($guardado) {
                return view('errores.exito', ['mensaje' => 'Sus datos han sido actualizados']);
            } else {
                return view('errores.error', ['mensaje' => 'Error en la modificación de datos']);
            }       
    }

    /**
     * Marca o desmarca el propio usuario para borrado (flag 'marcado_eliminar').
     *
     * Guarda el nuevo estado del checkbox.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function marcarBorrarPropia(Request $peticion)
    {
        // Obtener el usuario autenticado
        $usuario = Auth::check() ? User::find(Auth::id()) : null;

        if (!$usuario) {
            return view('errores.error', ['mensaje' => 'Usuario no encontrado o no autenticado.']);
        }

            // Guardar el estado anterior
            $estadoAnterior = $usuario->marcado_eliminar;

            // El checkbox 'cambiarmarcado' estará presente solo si está marcado
            // Si está presente, marcamos para eliminar (true), si no está presente, desmarcamos (false)
            $nuevoEstado = $peticion->has('cambiarmarcado');

            // Verificar si ha cambiado el estado
            if ($estadoAnterior == $nuevoEstado) {
                return view('errores.exito', ['mensaje' => 'No se ha modificado el status de su perfil']);
            }

            // Actualizar el estado
            $usuario->marcado_eliminar = $nuevoEstado;

            // Guardar los cambios
            $guardado = $usuario->save();

            if (!$guardado) {
                return view('errores.error', ['mensaje' => 'No se ha podido cambiar el status']);
            }

            // Si se marcó para eliminar, mostrar mensaje específico
            if ($usuario->marcado_eliminar) {
                return view('errores.exito', ['mensaje' => "Su cuenta de ha marcado para su eliminación.\nRecibirá una confirmación de los administradores cuando la cuenta haya sido eliminada.\nHasta entonces puede recuperar su cuenta desde su panel de control"]);
            } else {
                return view('errores.exito', ['mensaje' => 'Su cuenta ha sido recuperada correctamente']);
            }       
    }

    /**
     * Filtra y lista clientes "fantasma"
     * Clientes sin reportajes, pedidos ni citas.
     *
     * @return \Illuminate\View\View
     */
    public function filtrarClientesFantasma()
    {
        $usuarios = User::with('role') 
            ->whereHas('role', function ($consulta) {
                // Filtrar solo usuarios con rol "cliente"
                $consulta->where('nombre_role', NombreRole::CLIENTE->value);
            })
            // que no tienen reportajes, pedidos ni citas asociados
            ->doesntHave('reportajes')
            ->doesntHave('pedidos')
            ->doesntHave('cita')
            ->paginate(15);

        // Mensaje y vista de lista de resultados encontrados
        $mensaje = $usuarios->total() > 0 ? "Clientes fantasma encontrados: {$usuarios->total()}" : "No se han encontrado clientes fantasma.";
        return view('parciales.listados.listausuarios', compact('usuarios', 'mensaje'));
    }

}

