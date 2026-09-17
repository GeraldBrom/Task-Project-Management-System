<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const loading = ref(false);
const errorMessage = ref('');

async function handleSubmit() {
  loading.value = true;
  errorMessage.value = '';

  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/api/registration', form);
    router.push('/tasks');
  } catch (error) {
    if (error.response?.data?.errors) {
      const firstError = Object.values(error.response.data.errors)[0];
      errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError;
    } else {
      errorMessage.value = 'Произошла ошибка при регистрации';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="page">
    <div class="auth-card">
      <h1>Регистрация</h1>

      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="name">Имя</label>
          <input
              id="name"
              type="text"
              v-model="form.name"
              placeholder="Введите имя"
              required
          />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input
              id="email"
              type="email"
              v-model="form.email"
              placeholder="Введите email"
              required
          />
        </div>

        <div class="form-group">
          <label for="password">Пароль</label>
          <input
              id="password"
              type="password"
              v-model="form.password"
              placeholder="Минимум 8 символов"
              required
          />
        </div>

        <div class="form-group">
          <label for="password_confirmation">Подтверждение пароля</label>
          <input
              id="password_confirmation"
              type="password"
              v-model="form.password_confirmation"
              placeholder="Повторите пароль"
              required
          />
        </div>

        <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

        <button type="submit" class="submit-btn" :disabled="loading">
          {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
        </button>
      </form>

      <p class="auth-link">
        Уже есть аккаунт?
        <router-link to="/login">Войти</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f5f5f5;
}

.auth-card {
  background: #ffffff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 400px;
}

.auth-card h1 {
  margin: 0 0 24px;
  font-size: 24px;
  text-align: center;
  color: #1a1a1a;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-size: 14px;
  color: #333;
}

.form-group input {
  width: 100%;
  padding: 10px 12px;
  font-size: 14px;
  border: 1px solid #d0d0d0;
  border-radius: 6px;
  box-sizing: border-box;
}

.form-group input:focus {
  outline: none;
  border-color: #1a1a1a;
}

.error-text {
  color: #d32f2f;
  font-size: 13px;
  margin: 8px 0;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  font-size: 15px;
  background: #1a1a1a;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 8px;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.auth-link {
  text-align: center;
  margin-top: 20px;
  font-size: 14px;
  color: #666;
}

.auth-link a {
  color: #1a1a1a;
  text-decoration: underline;
}
</style>
