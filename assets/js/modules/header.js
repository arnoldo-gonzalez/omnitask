import { seemIsLogged } from "/assets/js/modules/auth.js";

const ul = document.getElementById("header-ul");

export function changeNav(pag) {
    const ok = seemIsLogged();

    if (ok) {
        ul.innerHTML = `
        <li><a class="${pag === "index" ? "paginactiva" : ""}" href="/">Inicio</a></li>
        <li><a class="${pag === "tasks" ? "paginactiva" : ""}" href="/user/tasks">Ir a tus tareas</a></li>
        `;
    } else {
        ul.innerHTML = `
        <li><a class="${pag === "index" ? "paginactiva" : ""}" href="/">Inicio</a></li>
        <li><a class="${pag === "sign_in" ? "paginactiva" : ""}" href="/user/sign_in">Iniciar sesión</a></li>
        <li><a class="${pag === "sign_up" ? "paginactiva" : ""}" href="/user/sign_up">Registrarse</a></li>
        `;
    }
    
}
