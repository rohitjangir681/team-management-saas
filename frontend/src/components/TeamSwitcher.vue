<script setup lang="ts">
import type { Company } from '@/types';
import axios from 'axios';
import api from '@/lib/axios';
import { computed, onMounted, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

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


const authStore = useAuthStore();

// Access 'user' directly from the store to maintain reactivity
const user = computed(() => authStore.user);

const getCompanyDetail = computed(() => {

    if(!user.value?.companies) return null;

    return user.value?.companies?.find((company: any) => {
        return company.id === user.value?.current_company_id;
    });
});

const currentUserRole = computed(() => {
    return getCompanyDetail.value?.pivot.role_name || 'No Role';
});
</script>

<template>
  <div class="px-3 py-2 space-y-1">
    <select
      v-model="activeId"
      @change="switchTeam"
      :disabled="loading"
      class="w-full bg-gray-800 text-white text-sm rounded-md border-none focus:ring-2 focus:ring-blue-500 cursor-pointer disabled:opacity-50"
    >
      <option v-for="company in companies" :key="company.id" :value="company.id">
        {{ company.name }}
      </option>
    </select>

    <div class="px-1">
      <span class="truncate text-xs text-muted-foreground capitalize font-medium">
        {{ currentUserRole }}
      </span>
    </div>
  </div>
</template>
