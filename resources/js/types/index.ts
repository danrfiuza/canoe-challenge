import { DefineComponent } from "vue";

export interface Fund {
    id: number;
    name: string;
    start_year: number;
    fund_manager: {
        name: string;
    };
}

export interface FundAlias {
    id: number | null;
    alias: string;
    fund_id: number;
}

export interface FundCompany {
    id: number | null;
    company_id: string;
    fund_id: number;
}

export interface Company {
    id: number | null;
    name: string;
}

export interface Action {
    label: string;
    callback: () => void;
}