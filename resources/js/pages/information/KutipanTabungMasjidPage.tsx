import React, { useState, useEffect, useMemo } from 'react';
import api from '@/lib/api';
import type { KutipanItem } from '@/types';

// Types
interface DailySummary {
    year: number;
    month: number;
    monthName: string;
    week: number;
    day: number;
    dayName: string;
    dayTotal: number;
}

interface WeeklySummary {
    year: number;
    month: number;
    monthName: string;
    week: number;
    weekTotal: number;
}

interface MonthlySummary {
    year: number;
    month: number;
    monthName: string;
    monthTotal: number;
}

interface YearlySummary {
    year: number;
    yearTotal: number;
}

interface SummariesData {
    daily: DailySummary[];
    weekly: WeeklySummary[];
    monthly: MonthlySummary[];
    yearly: YearlySummary[];
}

// Components
const Table: React.FC<{
    data: any[];
    columns: { key: string; label: string; format?: (val: any) => string }[];
    title?: string;
}> = ({ data, columns, title }) => {
    if (data.length === 0) {
        return <div className="text-center mt-4">Tiada rekod dijumpai</div>;
    }

    return (
        <table className="table table-striped table-bordered" style={{ width: '100%' }}>
            {title && <caption className="caption-top text-end fs-6">{title}</caption>}
            <thead>
                <tr>
                    {columns.map((col) => (
                        <th key={col.key}>{col.label}</th>
                    ))}
                </tr>
            </thead>
            <tbody>
                {data.map((row, idx) => {
                    const stableKey = `${row.year}-${row.month}-${row.day}-${row.week}-${idx}`;
                    return (
                        <tr key={stableKey}>
                            {columns.map((col) => (
                                <td key={`${stableKey}-${col.key}`}>
                                    {col.format ? col.format(row[col.key]) : row[col.key]?.toString() ?? '-'}
                                </td>
                            ))}
                        </tr>
                    );
                })}
            </tbody>
        </table>
    );
};

const KutipanTabungMasjidPage: React.FC = () => {
    const [kutipanList, setKutipanList] = useState<Omit<KutipanItem, 'created_at' | 'updated_at'>[]>([]);
    const [summaries, setSummaries] = useState<SummariesData | null>(null);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState<'summary' | 'detailed'>('summary');
    const [summarySubTab, setSummarySubTab] = useState<'daily' | 'weekly' | 'monthly' | 'yearly'>('daily');

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [kutipanRes, summariesRes] = await Promise.all([
                    api.get<{ data: Omit<KutipanItem, 'created_at' | 'updated_at'>[] }>('/api/v1/kutipan'),
                    api.get<{ data: SummariesData }>('/api/v1/kutipan/summaries'),
                ]);

                setKutipanList(kutipanRes.data.data);
                setSummaries(summariesRes.data.data);
            } catch (err) {
                console.error('Failed to fetch kutipan:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const formatCurrency = (amount: number): string => {
        return `RM ${amount.toFixed(2)}`;
    };

    const dailyColumns = useMemo(() => [
        { key: 'year', label: 'Tahun' },
        { key: 'monthName', label: 'Bulan' },
        { key: 'week', label: 'Minggu', format: (v: number) => `Minggu ${v}` },
        { key: 'dayName', label: 'Hari' },
        { key: 'dayTotal', label: 'Jumlah (RM)', format: formatCurrency },
    ], []);

    const weeklyColumns = useMemo(() => [
        { key: 'year', label: 'Tahun' },
        { key: 'monthName', label: 'Bulan' },
        { key: 'week', label: 'Minggu', format: (v: number) => `Minggu ${v}` },
        { key: 'weekTotal', label: 'Jumlah (RM)', format: formatCurrency },
    ], []);

    const monthlyColumns = useMemo(() => [
        { key: 'year', label: 'Tahun' },
        { key: 'monthName', label: 'Bulan' },
        { key: 'monthTotal', label: 'Jumlah (RM)', format: formatCurrency },
    ], []);

    const yearlyColumns = useMemo(() => [
        { key: 'year', label: 'Tahun' },
        { key: 'yearTotal', label: 'Jumlah (RM)', format: formatCurrency },
    ], []);

    const detailColumns = useMemo(() => [
        { key: 'day_name_ms', label: 'Hari' },
        { key: 'day_total', label: 'Jumlah Harian (RM)', format: formatCurrency },
        { key: 'week_total', label: 'Jumlah Minggu (RM)', format: formatCurrency },
        { key: 'month_total', label: 'Jumlah Bulan (RM)', format: formatCurrency },
        { key: 'year_total', label: 'Jumlah Tahun (RM)', format: formatCurrency },
        { key: 'week', label: 'Minggu', format: (v: number) => `Minggu ${v}` },
        { key: 'month_name_ms', label: 'Bulan' },
        { key: 'year', label: 'Tahun' },
    ], []);

    return (
        <>
            <style>{`
                .page-title-responsive {
                    font-size: 18px;
                }
                @media (min-width: 576px) {
                    .page-title-responsive {
                        font-size: 20px;
                    }
                }
                @media (max-width: 768px) {
                    .table { font-size: 12px; }
                    .table th, .table td { padding: 0.5rem 0.25rem; }
                    .nav-tabs .nav-link {
                        padding: 0.5rem 0.75rem;
                        font-size: 14px;
                    }
                }
            `}</style>
            <div className='row'>
                <div className='col-12 col-lg-9'>
                    <h6 className='text mt-3 page-title-responsive'>
                        <b>Kutipan Tabung Masjid</b>
                    </h6>
                    <p className='text mt-2 mb-3' style={{ fontSize: '14px' }}>
                        <i>Jumlah kutipan adalah di dalam Ringgit Malaysia (RM)</i>
                    </p>

                    {/* Tab Buttons */}
                    <div className='mb-3 d-flex flex-wrap gap-2'>
                        <button
                            className={`btn ${activeTab === 'summary' ? 'btn-primary' : 'btn-secondary'}`}
                            onClick={() => setActiveTab('summary')}
                        >
                            Ringkasan Kutipan
                        </button>
                        <button
                            className={`btn ${activeTab === 'detailed' ? 'btn-primary' : 'btn-secondary'}`}
                            onClick={() => setActiveTab('detailed')}
                        >
                            Senarai Lengkap
                        </button>
                    </div>

                    <div className='mt-3 mb-5 p-2 p-md-3 border rounded'>
                        {loading ? (
                            <p>Memuat data kutipan...</p>
                        ) : activeTab === 'summary' ? (
                            <>
                                {/* Summary Sub-Tabs */}
                                <ul className='nav nav-tabs mb-3 flex-wrap' role='tablist'>
                                    {['daily', 'weekly', 'monthly', 'yearly'].map((tab) => (
                                        <li key={tab} className='nav-item' role='presentation'>
                                            <a
                                                className={`nav-link ${summarySubTab === tab ? 'active' : ''}`}
                                                href={`#${tab}`}
                                                onClick={(e) => {
                                                    e.preventDefault();
                                                    setSummarySubTab(tab as any);
                                                }}
                                                role='tab'
                                                aria-selected={summarySubTab === tab}
                                            >
                                                {tab === 'daily' ? 'Harian' :
                                                 tab === 'weekly' ? 'Mingguan' :
                                                 tab === 'monthly' ? 'Bulanan' : 'Tahunan'}
                                            </a>
                                        </li>
                                    ))}
                                </ul>

                                <div className='tab-content'>
                                    {summarySubTab === 'daily' && (
                                        <div className='tab-pane fade show active'>
                                            <Table data={summaries?.daily || []} columns={dailyColumns} />
                                        </div>
                                    )}
                                    {summarySubTab === 'weekly' && (
                                        <div className='tab-pane fade show active'>
                                            <Table data={summaries?.weekly || []} columns={weeklyColumns} />
                                        </div>
                                    )}
                                    {summarySubTab === 'monthly' && (
                                        <div className='tab-pane fade show active'>
                                            <Table data={summaries?.monthly || []} columns={monthlyColumns} />
                                        </div>
                                    )}
                                    {summarySubTab === 'yearly' && (
                                        <div className='tab-pane fade show active'>
                                            <Table data={summaries?.yearly || []} columns={yearlyColumns} />
                                        </div>
                                    )}
                                </div>
                            </>
                        ) : (
                            <>
                                <Table data={kutipanList} columns={detailColumns} />
                            </>
                        )}
                    </div>
                </div>
                <div className='col-12 col-lg-3 mt-4 mt-lg-3'>
                    <div className='card'>
                        <div className='card-body'>
                            <h6 className='card-title page-title-responsive'>
                                <b>Jom Infaq</b>
                            </h6>
                            <p className='card-text' style={{ fontSize: '14px' }}>
                                <i>Sumbangan boleh dilakukan dengan mengimbas atau klik kod QR</i>
                            </p>
                            <div className='text-center mt-3'>
                                <a href='https://infaqpay.my/go/masjidalmustaghfirinsungaitiram' target='_blank' rel='noopener noreferrer'>
                                    <img
                                        className='img-fluid'
                                        style={{ maxWidth: '100%', height: 'auto', maxHeight: '250px' }}
                                        src='/images/infaq_qr.png'
                                        alt='Infaq QR'
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default KutipanTabungMasjidPage;
