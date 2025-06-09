<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const feedbacks = ref([])
const satisfactions = ref([])

const formFeedback = ref({
  firstName: '',
  lastName: '',
  email: '',
  comment: ''
})

const formSatisfaction = ref({
  rating: null
})

const loadFeedbacks = async () => {
  const response = await axios.get('http://symfony/api/feedback', {
    headers: { Accept: 'application/json' }
  })
  feedbacks.value = response.data || []
}

const loadSatisfactions = async () => {
  const response = await axios.get('http://symfony/api/satisfactions', {
    headers: { Accept: 'application/json' }
  })
  satisfactions.value = response.data || []
}

const submitFeedback = async () => {
  try {
    const payload = {
      ...formFeedback.value,
      createdAt: new Date().toISOString()
    }
    await axios.post('http://symfony/api/feedback', payload)
    formFeedback.value = { firstName: '', lastName: '', email: '', comment: '' }
    console.log('formFeedback.value = ' + formFeedback.value)
    await loadFeedbacks()
  } catch (err) {
    console.error('Error submitting feedback:', err)
  }
}

const submitSatisfaction = async () => {
  try {
    console.log('formSatisfaction.value.rating = ' + formSatisfaction.value.rating)      
    const payload = {
      rating: formSatisfaction.value.rating,
      createdAt: new Date().toISOString()
    }
    await axios.post('http://symfony/api/satisfactions', payload)
    formSatisfaction.value.rating = null
    await loadSatisfactions()
  } catch (err) {
    console.error('Error submitting satisfaction:', err)
  }
}

onMounted(() => {
  loadFeedbacks()
  loadSatisfactions()
})
</script>

<template>
  <h1>Submit Feedback</h1>
  <form @submit.prevent="submitFeedback">
    <input v-model="formFeedback.firstName" placeholder="First name" required />
    <input v-model="formFeedback.lastName" placeholder="Last name" required />
    <input v-model="formFeedback.email" type="email" placeholder="Email" required />
    <textarea v-model="formFeedback.comment" placeholder="Comment" required></textarea>
    <button type="submit">Send Feedback</button>
  </form>

  <h2>Submit Satisfaction Rating</h2>
  <form @submit.prevent="submitSatisfaction">
    <label>Select a rating (0–10):</label>
    <select v-model="formSatisfaction.rating" required>
      <option disabled value="">Please select</option>
      <option v-for="n in 11" :key="n-1" :value="n-1">{{ n - 1 }}</option>
    </select>
    <button type="submit">Send Satisfaction</button>
  </form>

  <h2>All Feedbacks</h2>
  <ul>
    <li v-for="f in feedbacks" :key="f.id">
      {{ f.firstName }} {{ f.lastName }} - {{ f.comment }}
    </li>
  </ul>

  <h2>Satisfaction Ratings</h2>
  <ul>
    <li v-for="s in satisfactions" :key="s.id">
      Rating: {{ s.rating }}
    </li>
  </ul>
</template>

<style scoped>
form {
  margin-bottom: 2rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  max-width: 400px;
}
input, textarea, select {
  padding: 0.5rem;
  font-size: 1rem;
}
</style>
