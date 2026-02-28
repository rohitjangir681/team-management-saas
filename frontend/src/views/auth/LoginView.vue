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
                <Button variant="link">Sign Up</Button>
            </CardAction>
        </CardHeader>
        <CardContent>
            <form>
                <div class="flex flex-col gap-6">
                    <div class="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input id="email" type="email" placeholder="m@example.com" />
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center justify-between w-full">
                            <Label htmlFor="password">Password</Label>
                            <a href="#" class="inline-block text-sm underline-offset-4 hover:underline">
                                Forgot your password?
                            </a>
                        </div>
                        <Input id="password" type="password" required />
                    </div>
                </div>
            </form>
        </CardContent>
        <CardFooter class="flex-col gap-2">
            <Button type="submit" class="w-full">
                Login
            </Button>
            <Button variant="outline" class="w-full">
                Login with Google
            </Button>
        </CardFooter>
    </Card>
  </div>
</template>