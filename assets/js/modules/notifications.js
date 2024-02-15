let interval = localStorage.getItem("noti_interval");
const notifiedTasks = {};

export const requestPermission = () => {
    return Notification.requestPermission();
};

const sendNotification = (title, body, delay) => {
    setTimeout(() => {
        new Notification(title, {body, icon: "/assets/imgs/relojsito.svg"});
    }, delay);
};

const filterByTime = ({id, datetime_start, datetime_finish}, actualTime) => {
    const start = new Date(datetime_start);
    const finish = new Date(datetime_finish);
    const result = start.getTime() <= actualTime && actualTime < finish.getTime() && !(id in notifiedTasks);
    notifiedTasks[id] = true;
    return result;
};

export const stopInterval = () => {
    if (localStorage.getItem("noti_interval") === null) return;
    clearInterval(parseInt(interval));
    localStorage.removeItem("noti_interval");
    interval = null;
};

export const enableNotifications = async () => {
    if (interval !== null || localStorage.getItem("noti_interval") !== null) stopInterval();

    interval = setInterval(() => {
        const actualTime = Date.now();
        const tasks = typeof(localStorage.getItem("tasks")) === "string" ? JSON.parse(localStorage.getItem("tasks")) : [];
        const actual_tasks = tasks.filter(task => filterByTime(task, actualTime));

        if (actual_tasks.length === 0) return;

        let delay = 0;
        actual_tasks.forEach( ({title, datetime_finish, subtasks}, i) => {
            sendNotification(`Es hora de la tarea: ${title}`, `Es hora de realizar la tarea llamada ${title}, tienes hasta el ${datetime_finish} para terminarla`, delay);

            delay += 500;

            const actualSubtasks = subtasks.filter(subtask => filterByTime(subtask, actualTime));
            actualSubtasks.forEach( ({title: subtitle, datetime_finish: subdatetime_finish}) => {
                sendNotification(`Es momento de ${subtitle}`, `Es momento de avanzar con la tarea ${title} realizando la subtarea ${subtitle}, tienes hasta el ${subdatetime_finish} para completar esta subtarea`, delay);
                delay += 500;
            });

            delay += 800;
        })
    }, 5000);

    localStorage.setItem("noti_interval", interval);
}
