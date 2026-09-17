<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const tasks = ref([])
const newTask = ref({
  title: '',
  description: ''
})

// Получение всех задач
const fetchTasks = async () => {
  try {
    const response = await axios.get('/api/tasks')
    tasks.value = response.data
  } catch (error) {
    console.error('Failed to fetch tasks:', error)
  }
}

// Создание задачи
const createTask = async () => {
  try {
    const response = await axios.post('/api/tasks', newTask.value)
    tasks.value.unshift(response.data)
    newTask.value = { title: '', description: '' }
  } catch (error) {
    console.error('Failed to create task:', error)
  }
}

// Обновление задачи
const updateTask = async (task) => {
  try {
    const response = await axios.put(`/api/tasks/${task.id}`, {
      title: task.title,
      description: task.description
    })
    Object.assign(task, response.data)
  } catch (error) {
    console.error('Failed to update task:', error)
  }
}

// Удаление задачи
const deleteTask = async (taskId) => {
  try {
    await axios.delete(`/api/tasks/${taskId}`)
    tasks.value = tasks.value.filter(t => t.id !== taskId)
  } catch (error) {
    console.error('Failed to delete task:', error)
  }
}

// Выход
const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    router.push('/login')
  } catch (error) {
    console.error('Logout failed:', error)
    router.push('/login')
  }
}

onMounted(() => {
  fetchTasks()
})
</script>

<template>
  <main class="tasks-page">
    <div class="tasks-container">
      <header class="tasks-header">
        <div>
          <p class="tasks-header__eyebrow">Личный список</p>
          <h1>Мои задачи</h1>
          <p class="tasks-header__subtitle">Планируйте дела и не забывайте о важном.</p>
        </div>

        <button class="logout-button" type="button" @click="handleLogout">
          Выйти
        </button>
      </header>

      <section class="task-form-card">
        <h2>Новая задача</h2>

        <form class="task-form" @submit.prevent="createTask">
          <div class="task-form__field">
            <label for="new-task-title">Название</label>
            <input
                id="new-task-title"
                v-model="newTask.title"
                type="text"
                placeholder="Например, подготовить отчёт"
                required
            />
          </div>

          <div class="task-form__field">
            <label for="new-task-description">Описание <span>необязательно</span></label>
            <textarea
                id="new-task-description"
                v-model="newTask.description"
                rows="3"
                placeholder="Добавьте детали задачи"
            ></textarea>
          </div>

          <button class="primary-button" type="submit">
            <span class="primary-button__icon">+</span>
            Добавить задачу
          </button>
        </form>
      </section>

      <section class="tasks-list-section">
        <div class="tasks-list-section__header">
          <h2>Активные задачи</h2>
          <span class="tasks-count">{{ tasks.length }} задач</span>
        </div>

        <div v-if="tasks.length === 0" class="empty-state">
          <div class="empty-state__icon">✓</div>
          <h3>Список задач пока пуст</h3>
          <p>Добавьте первую задачу через форму выше.</p>
        </div>

        <article v-for="task in tasks" :key="task.id" class="task-card">
          <div class="task-card__content">
            <button class="task-card__status task-card__status--pending" type="button">
              Активна
            </button>

            <div class="task-card__details">
              <input
                  v-model="task.title"
                  class="task-card__title"
                  type="text"
                  @blur="updateTask(task)"
              />
              <textarea
                  v-model="task.description"
                  class="task-card__description"
                  rows="2"
                  @blur="updateTask(task)"
                  placeholder="Описание задачи..."
              ></textarea>
            </div>
          </div>

          <div class="task-card__actions">
            <button class="text-button" type="button" @click="updateTask(task)">
              Сохранить
            </button>
            <button class="delete-button" type="button" @click="deleteTask(task.id)">
              Удалить
            </button>
          </div>
        </article>
      </section>
    </div>
  </main>
</template>

<style scoped>
:global(*) {
  box-sizing: border-box;
}

:global(body) {
  margin: 0;
  color: #202020;
  background: #f5f6f8;
  font-family: Inter, Arial, sans-serif;
}

.tasks-page {
  min-height: 100vh;
  padding: 48px 20px 72px;
}

.tasks-container {
  width: 100%;
  max-width: 880px;
  margin: 0 auto;
}

.tasks-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 30px;
}

.tasks-header__eyebrow {
  margin: 0 0 8px;
  color: #757575;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.tasks-header h1 {
  margin: 0;
  font-size: clamp(30px, 5vw, 42px);
  font-weight: 700;
  letter-spacing: -0.04em;
}

.tasks-header__subtitle {
  margin: 10px 0 0;
  color: #6b6b6b;
  font-size: 15px;
}

.logout-button,
.primary-button,
.text-button,
.delete-button,
.task-card__status,
.task-card__title {
  font: inherit;
  cursor: pointer;
}

.logout-button {
  padding: 10px 15px;
  color: #3e3e3e;
  background: #ffffff;
  border: 1px solid #dedede;
  border-radius: 8px;
  transition: background-color 0.15s ease;
}

.logout-button:hover {
  background: #f1f1f1;
}

.task-form-card,
.tasks-list-section {
  background: #ffffff;
  border: 1px solid #e6e6e6;
  border-radius: 14px;
  box-shadow: 0 8px 25px rgba(24, 28, 38, 0.04);
}

.task-form-card {
  padding: 25px;
}

.task-form-card h2,
.tasks-list-section h2 {
  margin: 0;
  font-size: 18px;
  letter-spacing: -0.015em;
}

.task-form {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr) auto;
  align-items: end;
  gap: 14px;
  margin-top: 18px;
}

.task-form__field label {
  display: block;
  margin-bottom: 7px;
  color: #4f4f4f;
  font-size: 13px;
  font-weight: 600;
}

.task-form__field label span {
  color: #979797;
  font-weight: 400;
}

.task-form input,
.task-form textarea {
  display: block;
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

.task-form textarea {
  min-height: 44px;
  resize: vertical;
}

.task-form input:focus,
.task-form textarea:focus {
  border-color: #252525;
  box-shadow: 0 0 0 3px rgba(35, 35, 35, 0.1);
}

.primary-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 44px;
  padding: 0 17px;
  color: #fff;
  background: #222222;
  border: 1px solid #222222;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  white-space: nowrap;
  transition: background-color 0.15s ease, transform 0.15s ease;
}

.primary-button:hover {
  background: #000;
}

.primary-button:active {
  transform: translateY(1px);
}

.primary-button__icon {
  font-size: 20px;
  font-weight: 400;
  line-height: 1;
}

.tasks-list-section {
  margin-top: 22px;
  overflow: hidden;
}

.tasks-list-section__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 25px;
  border-bottom: 1px solid #eeeeee;
}

.tasks-count {
  padding: 5px 9px;
  color: #646464;
  background: #f3f3f3;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}

.empty-state {
  padding: 54px 24px 58px;
  text-align: center;
}

.empty-state__icon {
  display: grid;
  width: 42px;
  height: 42px;
  margin: 0 auto 14px;
  color: #2d7d46;
  background: #e7f5ea;
  border-radius: 50%;
  place-items: center;
  font-size: 20px;
  font-weight: 700;
}

.empty-state h3 {
  margin: 0;
  font-size: 16px;
}

.empty-state p {
  margin: 8px 0 0;
  color: #777;
  font-size: 14px;
}

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
  margin: 6px 0 0;
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

.delete-button {
  color: #b03a3a;
}

@media (max-width: 720px) {
  .tasks-page {
    padding-top: 28px;
  }

  .tasks-header {
    align-items: center;
  }

  .task-form {
    grid-template-columns: 1fr;
  }

  .primary-button {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .tasks-page {
    padding-right: 14px;
    padding-left: 14px;
  }

  .task-form-card,
  .tasks-list-section__header,
  .task-card {
    padding-right: 17px;
    padding-left: 17px;
  }

  .tasks-header__subtitle {
    display: none;
  }

  .logout-button {
    padding: 9px 11px;
  }

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
