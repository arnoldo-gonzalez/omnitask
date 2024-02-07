# Backend: Referencia

#### Estructura de la DB

La tabla **users**  cuenta con la siguiente estructura:

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(25) NOT NULL,
    email VARCHAR(35) NOT NULL,
    user_password VARCHAR(40) NOT NULL,
    premium BOOLEAN DEFAULT 0 NOT NULL,
    pay_method VARCHAR(15)
);
```

La tabla **tasks** cuenta con la siguiente estructura:

```sql
CREATE TABLE tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(50) NOT NULL,
    datetime_start DATETIME NOT NULL,
    datetime_finish DATETIME NOT NULL,
    description VARCHAR(200) NOT NULL,
    fkuser INT UNSIGNED NOT NULL,

    FOREIGN KEY (fkuser) REFERENCES users(id) ON DELETE CASCADE
);
```

La tabla **subtasks** cuenta con la siguiente estructura:

```sql
CREATE TABLE subtasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(25) NOT NULL,
    datetime_start DATETIME NOT NULL,
    datetime_finish DATETIME NOT NULL,
    fktask INT UNSIGNED NOT NULL,

    FOREIGN KEY (fktask) REFERENCES tasks(id) ON DELETE CASCADE
);
```
