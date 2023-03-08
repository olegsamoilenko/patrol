import {ref} from 'vue'

export const feedbackStatusMap = ref([
    {value: null, title: 'Всі'},
    {value: 'Новий', title: 'Новий'},
    {value: 'Прочитано', title: 'Прочитано'},
    {value: 'В процесі', title: 'В процесі'},
    {value: 'Виконано', title: 'Виконано'},
    {value: 'Відхилено', title: 'Відхилено'},
])
