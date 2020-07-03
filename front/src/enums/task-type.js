export const TASK_TYPE = {
    "subscribe": "subscribe",
    "like": "like",
    "comment": "comment"
}

export const TASK_TYPE_TITLE = {
    "subscribe": "Подписчики",
    "like": "Лайки",
    "comment": "Комментарии"
}

export default {
    title: TASK_TYPE_TITLE,
    option: TASK_TYPE,
    values: Object.values(TASK_TYPE),
}