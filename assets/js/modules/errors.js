const errors_place = document.getElementById("errors_place");

const btns_errors_event = (btns) => {
    const listener = ({currentTarget}) => {
        const error_id = currentTarget.dataset["error"];
        const parent_error = document.getElementById(error_id);
        errors_place.removeChild(parent_error);
    };

    btns.forEach( btn => btn.addEventListener("click", listener) );
}

export const showErrors = errors => {
    let i = 0;
    const fragment = document.createDocumentFragment();
    for (const error of errors) {
        const new_task = document.createElement("article");
        new_task.classList.add("errors__error");
        new_task.id = "error-" + i;
        new_task.innerHTML = `
            <p>${error}</p>
            <button data-error=error-${i}><i class="fa-solid fa-xmark"></i></button>
        `;
        fragment.appendChild(new_task);
        i++
    }
    errors_place.appendChild(fragment);

    const close_btns = document.querySelectorAll("#errors_place .errors__error [data-error]");
    btns_errors_event(close_btns);
};


