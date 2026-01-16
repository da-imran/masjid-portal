// Common types for the application

export interface PrayerTimes {
    fajr: string;
    syuruk: string;
    dhuhr: string;
    asr: string;
    maghrib: string;
    isha: string;
}

export interface NewsItem {
    id: number;
    title_ms: string;
    title_en?: string;
    description_ms: string;
    description_en?: string;
    content_ms?: string;
    content_en?: string;
    image_name?: string;
    created_at: string;
    updated_at: string;
    published_at?: string;
    view_count: number;
    is_active?: boolean;
    is_featured?: boolean;
}

export interface Announcement {
    id: number;
    title_ms: string;
    title_en?: string;
    description_ms: string;
    description_en?: string;
    content_ms?: string;
    content_en?: string;
    priority: 'high' | 'medium' | 'low';
    image_name?: string;
    created_at: string;
    updated_at: string;
    expiry_date?: string;
    is_active?: boolean;
}

export interface KutipanItem {
    id: number;
    day: number;
    day_name_ms: string;
    day_total: string;
    week: number;
    week_total: string;
    month: number;
    month_name_ms: string;
    month_total: string;
    year: number;
    year_total: string;
    created_at: string;
    updated_at: string;
}

export interface VisitorCount {
    dailyCount: number;
    monthlyCount: number;
    overallCount: number;
}

export interface MaintenanceStatus {
    maintenance: boolean;
    message: string | null;
}

export interface DownloadItem {
    id: number;
    title_ms: string;
    title_en?: string;
    description_ms?: string;
    description_en?: string;
    category: 'jadual_kuliah' | 'nota_kuliah' | 'borang';
    file_name: string;
    file_path: string;
    file_size?: number;
    file_type?: string;
    download_count: number;
    is_active?: boolean;
    created_at: string;
    updated_at: string;
}
