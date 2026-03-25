export interface RegisterData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export interface LoginData {
    email: string;
    password: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
}

