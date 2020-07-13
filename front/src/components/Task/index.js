export const API_URL = "tasks"

import { TASK_TYPE } from "@/enums/task-type"
import { BAN_REASON } from "@/enums/ban-reason"

export const openTaskUrl = function(task) {
  window.open(task.url, "_blank")
}

export const BAN_REASON_MESSAGE = {
  [BAN_REASON.report]: [
    "Страница заблокирована из-за жалоб",
    "Она точно доступна и открыта для всех?",
  ],
  [BAN_REASON.cheat]: ["Страница заблокирована", "Обратитесь в поддержку"],
}

export const TASK_TYPE_META = {
  [TASK_TYPE.like]: {
    icon: "favorite",
    label: "лайков", // вы накрутили 13 лайков
    instruction: [
      "Сейчас откроется страница,",
      "там надо будет поставить лайк",
    ],
  },
  [TASK_TYPE.subscribe]: {
    icon: "person",
    label: "подписчиков",
    instruction: [
      "Сейчас откроется страница,",
      "на неё нужно будет подписаться",
    ],
  },
  [TASK_TYPE.comment]: {
    icon: "chat",
    label: "комментариев",
    instruction: [
      "Сейчас откроется страница, оставьте",
      "там какой-нибудь приятный комментарий",
    ],
  },
}
