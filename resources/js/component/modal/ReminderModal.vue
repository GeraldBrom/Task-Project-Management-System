<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    task: {
        type: Object,
        default: null
    },
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:show', 'save', 'delete'])

const reminderDateTime = ref('')

watch(() => props.task, (newTask) => {
    if (newTask && newTask.reminder_at) {
        const date = new Date(newTask.reminder_at)
        const year = date.getFullYear()
        const month = String(date.getMonth() + 1).padStart(2, '0')
        const day = String(date.getDate()).padStart(2, '0')
        const hours = String(date.getHours()).padStart(2, '0')
        const minutes = String(date.getMinutes()).padStart(2, '0')

        reminderDateTime.value = `${year}-${month}-${day}T${hours}:${minutes}`
    } else if (newTask) {
        const now = new Date()
        now.setHours(now.getHours() + 1)
        const year = now.getFullYear()
        const month = String(now.getMonth() + 1).padStart(2, '0')
        const day = String(now.getDate()).padStart(2, '0')
        const hours = String(now.getHours()).padStart(2, '0')
        const minutes = String(now.getMinutes()).padStart(2, '0')

        reminderDateTime.value = `${year}-${month}-${day}T${hours}:${minutes}`
    }
}, { immediate: true })

const handleSave = () => {
    emit('save', reminderDateTime.value)
    emit('update:show', false)
}

const handleDelete = () => {
    emit('delete')
    emit('update:show', false)
}

const handleClose = () => {
    emit('update:show', false)
}
</script>

<template>
    <div v-if="show" class="modal-overlay" @click.self="handleClose">
        <div class="modal">
            <h3>Напоминание</h3>

            <div class="modal__field">
                <label for="reminder-datetime">Дата и время</label>
                <input
                    id="reminder-datetime"
                    v-model="reminderDateTime"
                    type="datetime-local"
                />
            </div>

            <div class="modal__actions">
                <button class="modal__cancel" type="button" @click="handleClose">
                    Отмена
                </button>
                <button
                    v-if="task?.reminder_at"
                    class="modal__delete"
                    type="button"
                    @click="handleDelete"
                >
                    Удалить напоминание
                </button>
                <button class="modal__save" type="button" @click="handleSave">
                    Сохранить напоминание
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.modal h3 {
    margin: 0 0 20px;
    font-size: 18px;
}

.modal__field {
    margin-bottom: 18px;
}

.modal__field label {
    display: block;
    margin-bottom: 7px;
    color: #4f4f4f;
    font-size: 13px;
    font-weight: 600;
}

.modal__field input {
    width: 100%;
    padding: 11px 12px;
    color: #202020;
    background: #fff;
    border: 1px solid #d7d7d7;
    border-radius: 8px;
    font: inherit;
    font-size: 14px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.modal__field input:focus {
    border-color: #252525;
    box-shadow: 0 0 0 3px rgba(35, 35, 35, 0.1);
}

.modal__actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 10px;
}

.modal__cancel,
.modal__save,
.modal__delete {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #d7d7d7;
    background: #fff;
    cursor: pointer;
    transition: all 0.15s ease;
}

.modal__cancel:hover {
    background: #f5f5f5;
}

.modal__save {
    background: #222;
    color: #fff;
    border-color: #222;
}

.modal__save:hover {
    background: #000;
}

.modal__delete {
    color: #b03a3a;
    border-color: #b03a3a;
}

.modal__delete:hover {
    background: #b03a3a;
    color: #fff;
}
</style>
