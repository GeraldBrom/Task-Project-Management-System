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

const emit = defineEmits(['update:show', 'save'])

const editForm = ref({
    title: '',
    description: ''
})

watch(() => props.task, (newTask) => {
    if (newTask) {
        editForm.value = {
            title: newTask.title,
            description: newTask.description || ''
        }
    }
}, { immediate: true })

const handleSubmit = () => {
    emit('save', editForm.value)
    emit('update:show', false)
}

const handleClose = () => {
    emit('update:show', false)
}
</script>

<template>
    <div v-if="show" class="modal-overlay" @click.self="handleClose">
        <div class="modal">
            <h3>Редактировать задачу</h3>

            <form @submit.prevent="handleSubmit">
                <div class="modal__field">
                    <label for="edit-title">Название</label>
                    <input
                        id="edit-title"
                        v-model="editForm.title"
                        type="text"
                        required
                    />
                </div>

                <div class="modal__field">
                    <label for="edit-description">Описание</label>
                    <textarea
                        id="edit-description"
                        v-model="editForm.description"
                        rows="4"
                        placeholder="Описание задачи"
                    ></textarea>
                </div>

                <div class="modal__actions">
                    <button class="modal__cancel" type="button" @click="handleClose">
                        Отмена
                    </button>
                    <button class="modal__save" type="submit">
                        Сохранить
                    </button>
                </div>
            </form>
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

.modal__field input,
.modal__field textarea {
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

.modal__field input:focus,
.modal__field textarea:focus {
    border-color: #252525;
    box-shadow: 0 0 0 3px rgba(35, 35, 35, 0.1);
}

.modal__field textarea {
    min-height: 44px;
    resize: vertical;
}

.modal__actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 10px;
}

.modal__cancel,
.modal__save {
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
</style>
