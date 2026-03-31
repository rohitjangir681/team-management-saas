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
import { ref } from "vue";
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const form = ref({
	name: '',
	email: '',
	company_name: '',
	password: '',
	password_confirmation: ''
});

async function handleRegister()
{
	await authStore.register(form.value);
}

</script>

<template>
  <div class="min-h-screen flex items-center justify-center">
	<Card class="w-full max-w-sm">
		<CardHeader>
			<CardTitle>Create an account</CardTitle>
			<CardDescription>
				Enter your details below to create your account
			</CardDescription>
			<CardAction>
				<RouterLink :to="{name: 'login'}">
					<Button variant="link">Sign In</Button>
				</RouterLink>
			</CardAction> 
		</CardHeader>
		<CardContent>
			<form @submit.prevent="handleRegister">
				<div class="flex flex-col gap-6">

					<p v-if="authStore.error" class="text-red-500 text-sm text-center">
						{{ authStore.error }}
					</p>

					<div class="grid gap-2">
						<Label htmlFor="name">Name</Label>
						<Input 
							id="name" 
							type="text" 
							placeholder="Your name" 
							v-model="form.name"
						/>
						<p v-if="authStore.errors?.name" class="text-red-500 text-xs">
							{{ authStore.errors?.name[0] }}
						</p>
					</div>
					<div class="grid gap-2">
						<Label htmlFor="email">Email</Label>
						<Input 
							id="email" 
							type="email" 
							placeholder="m@example.com"
							v-model="form.email"
						/>
						<p v-if="authStore.errors?.email" class="text-red-500 text-xs">
							{{ authStore.errors?.email[0] }}
						</p>
					</div>
					<div class="grid gap-2">
						<Label htmlFor="company_name">Company</Label>
						<Input 
							id="company_name"
							type="text"
							placeholder="Company name"
							v-model="form.company_name"
						/>
					</div>
					<div class="grid gap-2">
						<Label htmlFor="password">Password</Label>
						<Input 
							id="password" 
							type="password" 
							v-model="form.password"
						/>
						<p v-if="authStore.errors?.password" class="text-red-500 text-xs">
							{{ authStore.errors?.password[0] }}
						</p>
					</div>
					<div class="grid gap-2">
						<Label htmlFor="password_confirmation">Confirm password</Label>
						<Input 
							id="password_confirmation" 
							type="password" 
							v-model="form.password_confirmation"
						/>
						<p v-if="authStore.errors?.password_confirmation" class="text-red-500 text-xs">
							{{ authStore.errors?.password_confirmation[0] }}
						</p>
					</div>
				</div>

				<CardFooter class="flex-col gap-2 pt-6">
					<Button type="submit" class="w-full">
						{{ authStore.loading ? 'Creating account...' : 'Create Account' }}
					</Button>
					<Button variant="outline" type="button" class="w-full">
						Register with Google
					</Button>
				</CardFooter>
			</form>
		</CardContent>
	</Card>
  </div>
</template>


