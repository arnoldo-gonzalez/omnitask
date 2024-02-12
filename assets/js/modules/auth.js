const checkLogged = (token, callback) => {
    return fetch("/user/actions/is_logged", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`
        }
    })
        .then(res => res.json())
        .then( ({ok}) => {
            return ok;
        })
}

export function seemIsLogged() {
    const account = JSON.parse(localStorage.getItem("account_data"));
    if (!account || !account.hasOwnProperty("token") || !account.hasOwnProperty("id") || !account.hasOwnProperty("name")) {
        return false;
    }
    return true;
}

export const redirectLogged = () => {
    const account = JSON.parse(localStorage.getItem("account_data"));
    if (account && account.hasOwnProperty("token") && account.hasOwnProperty("id") && account.hasOwnProperty("name")) {
        window.location.href = "/user/tasks";
    }
}

export const redirectNotLogged = () => {
    const account = JSON.parse(localStorage.getItem("account_data"));
    if (!account || !account?.hasOwnProperty("token") || !account?.hasOwnProperty("id") || !account?.hasOwnProperty("name")) {
        localStorage.removeItem("account_data");
        window.location.href = "/user/sign_in";
        return;
    }

    checkLogged(account["token"]).then((ok) => {
        if (ok) return;
        localStorage.removeItem("account_data");
        window.location.href = "/user/sign_in";
    });

    setInterval(() => checkLogged(account["token"]), 5000);
}
