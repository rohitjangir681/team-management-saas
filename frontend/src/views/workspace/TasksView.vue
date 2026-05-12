<script setup lang="ts">
import api from '@/lib/axios';
import type { Task } from '@/types';
import { onMounted, ref } from 'vue';

const tasks = ref<Task[]>([]);
const loading = ref(true);

const fetchTasks = async () => {
  try {
    const { data } = await api.get('/tasks');
    tasks.value = data.data;
  } catch (err) {
    console.error("Error fetching tasks:", err);
  } finally {
    loading.value = false;
  }
}

onMounted(fetchTasks);

</script>
<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold tracking-tight">Tasks</h1>
      <button class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium">
        + New Task
      </button>
    </div>

    <div v-if="loading" class="flex justify-center py-10">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
    </div>

    <div v-else class="grid gap-4">
      <div v-for="task in tasks" :key="task.id"
        class="p-4 bg-card border rounded-lg shadow-sm flex items-center justify-between">
        <div>
          <h3 class="font-medium">{{ task.title }}</h3>
          <p class="text-sm text-muted-foreground">{{ task.description }}</p>
        </div>
        <div class="text-xs uppercase bg-secondary px-2 py-1 rounded">
          {{ task.status }}
        </div>
      </div>
    </div>

    <div class="text-center py-20 border-2 border-dashed rounded-lg">
      <p class="text-muted-foreground">No tasks found in this workspace.</p>
    </div>

  </div>
</template>
