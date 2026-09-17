<script setup>
const props = defineProps({
    task: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['toggle-status', 'open-edit', 'open-reminder', 'delete'])

const truncateDescription = (desc, maxLen = 50) => {
    if (!desc) return ''
    return desc.length > maxLen ? desc.slice(0, maxLen) + '...' : desc
}

const formatReminder = (reminderAt) => {
    if (!reminderAt) return ''
    const date = new Date(reminderAt)
    return date.toLocaleString('ru-RU', {
        day: 'numeric',
        month: 'long',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    })
}
</script>

<template>
    <article class="task-card">
        <div class="task-card__content">
            <button
                class="task-card__status"
                :class="task.status === 'pending' ? 'task-card__status--pending' : 'task-card__status--completed'"
                type="button"
                @click="$emit('toggle-status', task)"
            >
                {{ task.status === 'pending' ? 'Активна' : 'Выполнена' }}
            </button>

            <div class="task-card__details">
                <button
                    class="task-card__title"
                    type="button"
                    @click="$emit('open-edit', task)"
                >
                    {{ task.title }}
                </button>
                <p class="task-card__description">
                    {{ truncateDescription(task.description) }}
                </p>
                <p v-if="task.reminder_at" class="task-card__reminder">
                    Напомнить: {{ formatReminder(task.reminder_at) }}
                </p>
            </div>
        </div>

        <div class="task-card__actions">
            <button
                class="text-button"
                type="button"
                @click="$emit('open-reminder', task)"
                :disabled="task.status === 'completed'"
            >
                {{ task.status === 'completed' ? 'Напоминание недоступно' : (task.reminder_at ? 'Изменить напоминание' : 'Напомнить') }}
            </button>
            <button class="delete-button" type="button" @click="$emit('delete', task.id)">
                Удалить
            </button>
        </div>
    </article>
</template>

<style scoped>
.task-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 25px;
    border-bottom: 1px solid #eeeeee;
}

.task-card:last-child {
    border-bottom: 0;
}

.task-card__content {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    min-width: 0;
}

.task-card__status {
    flex: 0 0 auto;
    padding: 6px 9px;
    border: 0;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.task-card__status--pending {
    color: #9a5a00;
    background: #fff1d6;
}

.task-card__status--completed {
    color: #216738;
    background: #e5f5e8;
}

.task-card__details {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.task-card__title {
    padding: 0;
    color: #202020;
    background: transparent;
    border: 0;
    font-size: 15px;
    font-weight: 650;
    text-align: left;
}

.task-card__title:hover {
    text-decoration: underline;
}

.task-card__description,
.task-card__reminder {
    margin: 0;
    color: #747474;
    font-size: 13px;
}

.task-card__reminder {
    color: #3e6f9f;
}

.task-card__actions {
    display: flex;
    flex: 0 0 auto;
    gap: 12px;
    align-items: center;
}

.text-button,
.delete-button {
    padding: 5px 0;
    background: transparent;
    border: 0;
    font-size: 13px;
}

.text-button {
    color: #235d91;
}

.text-button:disabled {
    color: #999;
    cursor: not-allowed;
}

.delete-button {
    color: #b03a3a;
}

@media (max-width: 480px) {
    .task-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .task-card__actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
