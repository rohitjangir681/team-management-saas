<script setup lang="ts">
import { onMounted, ref } from 'vue';
import api from '@/lib/axios';

interface Member {
  id: number;
  name: string;
  email: string;
  pivot: {
    role?: {
      name: string;
    }
  }
}

const members = ref<Member[]>([]);
const loading = ref(true);

const fetchTeamMembers = async () => {
  loading.value = true;
  try {
    const response = await api.get('/workspace/members');
    members.value = response.data.members;
  } catch (error) {
    console.error('Failed to load team members: ', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchTeamMembers();
});

</script>
<template>
  <div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold tracking-tight">Team Management</h1>
        <p class="text-sm text-muted-foreground">
          Manage users and roles inside your current workspace.
        </p>
      </div>
    </div>

    <div v-if="loading" class="flex items-center justify-center h-48">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500" />
    </div>

    <div v-else class="rounded-md border bg-card">
      <div class="relative w-full overflow-auto">
        <table class="w-full caption-bottom text-sm">
          <thead class="border-b bg-muted/50">
            <tr class="text-left font-medium text-muted-foreground">
              <th class="p-4">Name</th>
              <th class="p-4">Email</th>
              <th class="p-4">Role</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="member in members" :key="member.id" class="hover:bg-muted/50 transition-colors">
              <td class="p-4 font-medium">{{ member.name }}</td>
              <td class="p-4 text-muted-foreground">{{ member.email }}</td>
              <td class="p-4">
                <span
                  class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold capitalize bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                  {{ member.pivot?.role?.name || 'Member' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
