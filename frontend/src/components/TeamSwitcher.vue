<script setup lang="ts">
import type { Company } from '@/types';
import axios from 'axios';
import api from '@/lib/axios';
import { onMounted, ref } from 'vue';

const companies = ref<Company[]>([]);
const activeId = ref<number | null>(null);
const loading = ref(false);

const fetchData = async () => {
  try {
    // This endpoint should return the user's companies and current_company_id
    const { data } = await api.get('/auth/me');

    if (data.user) {
      companies.value = data.user.companies;
      activeId.value = data.user.current_company_id;
    }

  } catch (err) {
    console.error('Failed to fetch workspaces', err);
  }
}

const switchTeam = async () => {
  if (!activeId.value) return;
  loading.value = true;
  try {
    const response = await api.post('/workspace/switch', {
      company_id: activeId.value
    });

    // Force reload so Laravel Global Scopes (Multitenantable) take effect
    if(response.status === 200){
      window.location.reload();
    }
  } catch (err: any) {
    console.error('Full Error: ', err.response || err);
    alert("Switch failed: " + (err.response?.data?.message || "Unknown error"));
  } finally {
    loading.value = false;
  }
}

onMounted(fetchData);
</script>

<template>
  <div class="px-3 py-2">
    <select v-model="activeId" @change="switchTeam" :disabled="loading"
      class="w-full bg-gray-800 text-white text-sm rounded-md border-none focus:ring-2 focus:ring-blue-500">
      <option v-for="company in companies" :key="company.id" :value="company.id">
        {{ company.name }}
      </option>
    </select>
  </div>
</template>
