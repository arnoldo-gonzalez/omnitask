# Acciones de la Api

##### Acciones del usuario:

*Nota: El :id es un fragmento dinamico de la url que sera reemplazado con el id del usuario en cuerion*

*Nota: En cada metodo el servidor validara los datos, de igual manera, el frontend debera hacerlo*

- GET /user/sign_in  y  GET /user/sign_up: Renderiza el archivo html correspondiente si el usuario no esta logueado.

- POST /user/sign_in : Recive un objeto de la forma **{email: String, password: String}** con los datos del usuario. Al recibir la solicitud, se buscara la cuenta en la base de datos, si la encuentra devuelve un json de la forma **{error: null, code: 200, message: "Logged Successfuly"}**, en caso contrario se devuelve **{error: true, code: 401}**.

- POST /user/sign_up : Recive un objeto de la forma **{name: String, email: String, password: String, premium: Bool, pay_method: String?}** con los datos del usuario, si se pasa **premium: true** se debe especificar el metodo de pago, de otro modo debe asignarse **null**. Al recibir la solicitud, se intentara crear la cuenta en la base de datos, si se puede devuelve un json de la forma **{error: null, code: 200, message: null}**, si no se devuelve **{error: true, code: 400}**.

- DELETE /user/:id/actions/delete : Recive un objeto de la forma **{id: Int, email: String, password: String}** con los datos de la cuenta a borrar. Si se logra borrar se devuelve **{error: null, code: 200}**, si no se devuelve **{error: true, code: 400}**. *Nota: Aun falta determinar si es completamente necesario que reciba algo esta ruta.*

- PATCH /user/:id/actions/change : Se pasa un objeto de la forma **{id: int, email: String, password:String, changes: {Changes to be made}**. *Nota: Los 'Changes to me made' son de la forma **{field_to_change: newValue}**.*

##### Acciones de las tareas:

- GET /:username/tasks : Envia un json de la forma **{tasks: [{id: int, title: string, description: string, datetime: string}, ...]}**

- POST /:username/tasks/new : Recive un json de la forma **{title: String, description: String, time: String(HH:MM:SS)}**

hola
