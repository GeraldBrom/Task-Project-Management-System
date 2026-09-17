<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = reactive({
  mail: '',
  password: '',
});

const loading = ref(false);
const errorMessage = ref('');

async function handleSubmit() {
  loading.value = true;
  errorMessage.value = '';

  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/api/login', form);
    router.push('/tasks');
  } catch (error) {
    if (error.response?.data?.errors) {
      const firstError = Object.values(error.response.data.errors)[0];
      errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError;
    } else {
      errorMessage.value = 'Неверный логин или пароль';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="page">
    <div class="auth-card">
      <h1>Вход</h1>

      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="mail">Логин (email)</label>
          <input
              id="mail"
              type="email"
              v-model="form.mail"
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
              placeholder="Введите пароль"
              required
          />
        </div>

        <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

        <button type="submit" class="submit-btn" :disabled="loading">
          {{ loading ? 'Вход...' : 'Войти' }}
        </button>
      </form>

      <p class="auth-link">
        Нет аккаунта?
        <router-link to="/register">Зарегистрироваться</router-link>
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
