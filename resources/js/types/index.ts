// Common types for the application

export interface PrayerTimes {
    subuh: string;
    syuruk: string;
    zohor: string;
    asar: string;
    maghrib: string;
    isyak: string;
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

// RBAC Types
export interface Permission {
    id: number;
    role_id: number;
    name: string;
    description: string;
    is_active: boolean;
    created_at?: string;
    updated_at?: string;
}

export interface Role {
    id: number;
    name: string;
    description: string;
    is_active: boolean;
    permissions?: Permission[];
    created_at?: string;
    updated_at?: string;
}

// Permission categories for RBAC UI
export type PermissionAction = 'view' | 'create' | 'edit' | 'delete';

export interface PermissionModule {
    key: string;
    label: string;
    labelEn: string;
}

export const PERMISSION_MODULES: PermissionModule[] = [
    { key: 'users', label: 'Pengguna', labelEn: 'Users' },
    { key: 'roles', label: 'Peranan', labelEn: 'Roles' },
    { key: 'berita', label: 'Berita', labelEn: 'News' },
    { key: 'kemudahan', label: 'Kemudahan', labelEn: 'Facilities' },
    { key: 'takwim', label: 'Takwim', labelEn: 'Calendar' },
    { key: 'pengumuman', label: 'Pengumuman', labelEn: 'Announcements' },
];

export const PERMISSION_ACTIONS: { key: PermissionAction; label: string; labelEn: string }[] = [
    { key: 'view', label: 'Lihat', labelEn: 'View' },
    { key: 'create', label: 'Cipta', labelEn: 'Create' },
    { key: 'edit', label: 'Edit', labelEn: 'Edit' },
    { key: 'delete', label: 'Padam', labelEn: 'Delete' },
];
