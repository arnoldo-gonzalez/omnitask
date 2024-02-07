# Acciones de la Api

#### Acciones del usuario:

*Nota: El :id es un fragmento dinamico de la url que sera reemplazado con el id del usuario en cuerion*

*Nota: En cada metodo el servidor validara los datos, de igual manera, el frontend debera hacerlo*

- <mark>GET **/user/sign_in**  y  GET **/user/sign_up**:</mark> Renderiza el archivo html correspondiente.

- <mark>POST **/user/sign_in** :</mark> Recive un json de la forma: 
  
  ```json
  {"email": String, "password": String} 
  ```
  
  Devuelve un json de la forma:
  
  ```json
  // En caso de exito
  {    "ok": true, "token": String,
       "id": String, "name": String,
       "next_url": String
  }
  // En caso de error
  {"ok": false, "errors": String[]}
  ```
  
  *Nota: "nex_url" contiene la url a la que el usuario deberia ser redireccionado, la corecta redireccion queda a responsabilidad del cliente.*

- <mark>POST **/user/sign_up** :</mark> Recive un objeto de la forma:
  
  ```json
  {   "name": String, 
      "email": String, 
      "password": String, 
      "premium": String, 
      "pay_method": String
  }
  ```
  
  Devuelve un json de la forma ("url" es donde se debe redireccionar al usuario):
  
  ```json
  // En caso de exito
  {    "ok": true, "token": String,
       "id": String, "name": String,
       "next_url": String
  }
  // En caso de error
  {"ok": false, "errors": String[]}
  ```
  
  *Nota: **Premium** debe ser 'true' o 'false', si se pasa **premium: 'true'** se debe especificar un metodo de pago validos.*

- <mark>DELETE **/user/{id}/actions/delete** :</mark> Recive un objeto de la forma :
  
  ```json
  {"token": String, "email": String, "password": String}
  ```
  
  Devuelve objeto de la forma:
  
  ```json
  // En caso de exito
  {"ok": true, "next_url": String}
  // En caso de error
  {"ok": false, "errors": String[]}
  ```

- <mark>PATCH **/user/{id}/actions/change** :</mark> Recive un objeto de la forma 
  
  ```json
  {
      "token": String, 
      "email": String, 
      "password" :String, 
      "changes": {
          "field_to_change_1": newValue    
      }
  }
  ```
  
  Devuelve objeto de la forma:
  
  ```json
  // En caso de exito
  {"ok": true}
  // En caso de error
  {"ok": false, "errors": String[]}
  ```

- <mark>**GET /user/is_loged** :</mark> Valida si el usuario esta logueado, recive json de la forma:
  
  ```json
  {"token": String}
  ```
  
  Devuelve json de la forma:
  
  ```json
  // En caso de exito
  {"ok": true}
  // En caso de error
  {"ok": false, "errors": ["Is not logged"]}
  ```

- **<mark>GET /user/{id}/get_data :</mark>** Recive un json de la forma:
  
  ```json
  {"token": String}
  ```
  
  Devuelve json de la forma:
  
  ```json
  {    "id": String, "name": String, 
       "email": String, "premium": Bool,
       "pay_method": String?
  }
  ```

#### Acciones de las tareas:

- **<mark>GET /user/{id}/tasks :</mark>** Renderiza el html correspondiente si esta logueado pasandole las tareas del usuario en cuetion, de otro modo redirecciona a **/users/sign_in**. Las tareas se pasan mediante un JSON de la forma:
  
  ```json
  [
      {"id": int, 
      "title": string, 
      "description": string, 
      "datetime_start": String(YYYY-MM-DD HH:MM:SS), 
      "datetime_finish": String(YYYY-MM-DD HH:MM:SS), 
      "subtasks": [{
          "id": String,
          "title": String, 
          "datetime_start": String(YYYY-MM-DD HH:MM:SS), 
          "datetime_finish": string},
      ...]}, 
  ...]
  ```

- <mark>POST **/user/{id}/tasks/new** :</mark> Recive un json de la forma:
  
  ```json
  {    
      "title": String, 
      "description": String, 
      "datetime_start": String(YYYY-MM-DD HH:MM:SS),
      "datetime_end": String(YYYY-MM-DD HH:MM:SS)
  }
  ```
  
  Devuelve json de la forma:
  
  ```json
  // En caso de exito
  {"code": 200, error: null}
  // En caso de error
  {"code": 400, error: true}
  ```

- <mark>POST **/user/{id}/tasks/subtasks/new** :</mark> Recive un json de la forma:
  
  ```json
  {
      "title": String, 
      "id_parent_task": String, 
      "datetime_start": String,
      "datetime_finish": String
  }
  ```
  
  Devuelve json de la forma:
  
  ```json
  // Encaso de exito
  {"code": 200, "error": null, "id_subtask": String}
  // Encaso de Error
  {"code": 400, "error": true}
  ```

- **<mark>DELETE /user/{id}/tasks/delete :</mark>** Recive un json de la forma:
  
  ```json
  {"id": String}
  ```
  
  Devuelve json de la forma:
  
  ```json
  // En caso de exito
  {"code": 200, "error": null}
  // En caso de error
  {"code": 400, "error": true}
  ```

- <mark>**DELETE /user/{id}/tasks/subtasks/delete :**</mark> Recive un json de la forma:

```json
{"id_parent_task": String, "id_subtask": String}
```

        Devuelve json de la forma:

```json
// En caso de exito
{"code": 200, "error": null}
// En caso de error
{"code": 400, "error": true}
```
