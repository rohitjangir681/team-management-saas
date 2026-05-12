export interface Company {
  id: number;
  name: string;
  slug: string;
}

export interface User {
  id: number;
  name: string;
  current_company_id: number | null;
  companies: Company[];
}

export interface Task {
  id: number;
  title: string;
  description: string;
  status: string;
  company_id: number;
  assigned_to?: number;
  created_at: string;
}
