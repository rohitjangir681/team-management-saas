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

export interface Role {
  id: number;
  name: string;
  slug: string;
}

export interface Pivot {
  user_id: number;
  company_id: number;
  role_id: number;
  role_name: string;
  role: Role;
}

export interface Company {
  id: number;
  name: string;
  slug: string;
  pivot: Pivot;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    current_company_id: number | null;
    companies?: Company[];
}

