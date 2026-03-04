<script setup lang="ts">
import { Button } from "@/components/ui/button"
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";

const authStore = useAuthStore();

const form = ref({
    email: '',
    password: ''
});

async function handleLogin()
{
    await authStore.login(form.value);
}


</script>

<template>
  <div class="min-h-screen flex items-center justify-center">
    <Card class="w-full max-w-sm">
        <CardHeader>
            <CardTitle>Login to your account</CardTitle>
            <CardDescription>
                Enter your email below to login to your account
            </CardDescription>
            <CardAction>
                <RouterLink :to="{name: 'register'}">
                    <Button variant="link">Sign Up</Button>
                </RouterLink>
            </CardAction>
        </CardHeader>
        <CardContent>
            <!-- wrap footer buttons inside the same form so the login submit lives within it -->
            <form @submit.prevent="handleLogin">
                <div class="flex flex-col gap-6">
                    <p v-if="authStore.error" class="text-red-500 text-sm text-center">
						{{ authStore.error }}
					</p>
                    <div class="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input id="email" type="email" placeholder="m@example.com" v-model="form.email"/>
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center justify-between w-full">
                            <Label htmlFor="password">Password</Label>
                            <a href="#" class="inline-block text-sm underline-offset-4 hover:underline">
                                Forgot your password?
                            </a>
                        </div>
                        <Input id="password" type="password" v-model="form.password" />
                    </div>
                </div>

                <CardFooter class="flex-col gap-2 pt-6">
                    <Button type="submit" class="w-full">
                        Login
                    </Button>
                    <!-- Google login doesn't submit the form; use a normal button type to avoid accidental form submit -->
                    <Button variant="outline" type="button" class="w-full">
                        Login with Google
                    </Button>
                </CardFooter>
            </form>
        </CardContent>
    </Card>
  </div>
</template>