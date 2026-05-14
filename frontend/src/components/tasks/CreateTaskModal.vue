<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/lib/axios';
import type { Project } from '@/types'; // Import your Project interface
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';

// 1. Define the shape of our form data
interface TaskForm {
  title: string;
  description: string;
  project_id: number | string; // string for the initial empty state
  priority: 'low' | 'medium' | 'high';
  status: 'todo' | 'in_progress' | 'done';
}

const emit = defineEmits<{
  (e: 'task-created'): void
}>();

const open = ref(false);
const loading = ref(false);
const projects = ref<Project[]>([]); // 2. Type the projects array

// 3. Initialize form with types
const form = ref<TaskForm>({
  title: '',
  description: '',
  project_id: '',
  priority: 'medium',
  status: 'todo'
});

const fetchProjects = async (): Promise<void> => {
  try {
    const { data } = await api.get('/projects');
    // Laravel pagination returns the array inside 'data'\
    projects.value = data.projects;
  } catch (error) {
    console.error("Failed to load projects", error);
  }
};

const handleSubmit = async (): Promise<void> => {
  loading.value = true;
  try {
    await api.post('/tasks', form.value);

    // Reset form to defaults
    form.value = {
      title: '',
      description: '',
      project_id: '',
      priority: 'medium',
      status: 'todo'
    };

    open.value = false;
    emit('task-created');
  } catch (error) {
    console.error("Task creation failed", error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchProjects);
</script>
<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <Button variant="default">+ New Task</Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Create New Task</DialogTitle>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4 py-4">
        <div class="space-y-2">
          <label class="text-sm font-medium">Title</label>
          <Input v-model="form.title" placeholder="What needs to be done?" required />
        </div>

        <div class="space-y-2">
          <label class="text-sm font-medium">Project</label>
          <select v-model="form.project_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" required>
            <option value="" disabled>Select a project</option>
            <option v-for="project in projects" :key="project.id" :value="project.id">
              {{ project.name }}
            </option>
          </select>
        </div>

        <div class="space-y-2">
          <label class="text-sm font-medium">Description</label>
          <Textarea v-model="form.description" placeholder="Add some details..." />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <label class="text-sm font-medium">Priority</label>
            <select v-model="form.priority" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium">Status</label>
            <select v-model="form.status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
              <option value="todo">To Do</option>
              <option value="in_progress">In Progress</option>
              <option value="done">Done</option>
            </select>
          </div>
        </div>

        <DialogFooter>
          <Button type="submit" class="w-full" :disabled="loading">
            {{ loading ? 'Saving...' : 'Create Task' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
