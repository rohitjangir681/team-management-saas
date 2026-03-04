import { defineStore } from "pinia";
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from '@/lib/axios';
import type { RegisterData, LoginData } from "@/types/auth";

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const loading = ref(false);
    const error = ref('');
    const router = useRouter();
    const errors = ref<Record<string, string[]>>({});

    // Get CSRF cookie from Laravel (required before login/register)
    async function getCsrfCookie() {
        await api.get(`http://localhost:8000/sanctum/csrf-cookie`);
    }

    // register
   async function register(data: RegisterData)
    {
        loading.value =  true;
        error.value = '';
        errors.value = {};
        try{
            await getCsrfCookie();
            const response = await api.post('/auth/register', data);
            user.value = response.data.user;
            router.push({name: 'dashboard'});

        } catch(err: any){
            if(err.response?.status === 422){
                errors.value = err.response.data.errors
            } else {
                error.value = err.response?.data?.message || 'Something went wrong';
            }
        } finally{
            loading.value = false;
        }
    }

    // Login
    async function login(data: LoginData)
    {
        loading.value = true;
        error.value   = ''
        try{
            await getCsrfCookie();
            const response = await api.post('/auth/login', data);
            user.value = response.data.user;
            router.push({name: 'dashboard'});
        } catch(err: any){
            error.value = err.response?.data?.message || 'Something went wrong'
        } finally {
            loading.value = false;
        }
    }

    return {
        error,
        errors,
        register,
        loading,
        login
    }

});



