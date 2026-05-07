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
