<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import { SidebarProvider, SidebarInset, SidebarTrigger } from '@/components/ui/sidebar';


</script>
<template>
  <SidebarProvider>
    <AppSidebar />
     <SidebarInset>
      <header class="flex h-16 shrink-0 items-center gap-2 px-6 border-b transition-[width,height] ease-linear">
        <SidebarTrigger class="-ml-1" />
        <div class="h-4 w-[1px] bg-gray-200 mx-2"></div>
        <h1 class="text-sm font-medium text-gray-600">Workspace</h1>
      </header>
      <main class="flex-1 overflow-y-auto bg-gray-50/50">
        <div class="mx-auto max-w-7xl p-6">
          <RouterView />
        </div>
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>



