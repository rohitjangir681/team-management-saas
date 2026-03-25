<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import { SidebarProvider, SidebarInset, SidebarTrigger } from '@/components/ui/sidebar';


</script>
<template>
  <SidebarProvider>
    <AppSidebar />
     <SidebarInset>
      <header class="flex h-16 items-center gap-2 px-6 border-b border-gray-100">
        <SidebarTrigger class="-ml-1" />
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-500">Dashboard</span>
      </header>
      <main class="p-6">
        <RouterView />
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>



