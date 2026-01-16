import React, { useState, useEffect, useRef } from 'react';
import api from '@/lib/api';
import type { KutipanItem } from '@/types';

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

const KutipanTabungMasjidPage: React.FC = () => {
    const [kutipanList, setKutipanList] = useState<KutipanItem[]>([]);
    const [dailySummaries, setDailySummaries] = useState<DailySummary[]>([]);
    const [weeklySummaries, setWeeklySummaries] = useState<WeeklySummary[]>([]);
    const [monthlySummaries, setMonthlySummaries] = useState<MonthlySummary[]>([]);
    const [yearlySummaries, setYearlySummaries] = useState<YearlySummary[]>([]);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState<'summary' | 'detailed'>('summary');
    const [tabKey, setTabKey] = useState(0);

    const dailyTableRef = useRef<HTMLTableElement>(null);
    const weeklyTableRef = useRef<HTMLTableElement>(null);
    const monthlyTableRef = useRef<HTMLTableElement>(null);
    const yearlyTableRef = useRef<HTMLTableElement>(null);
    const detailTableRef = useRef<HTMLTableElement>(null);

    useEffect(() => {
        const fetchKutipan = async () => {
            try {
                const response = await api.get<KutipanItem[]>('/api/kutipan');
                setKutipanList(response.data);
                processData(response.data);
            } catch (err) {
                console.error('Failed to fetch kutipan:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchKutipan();
    }, []);

    const processData = (data: KutipanItem[]) => {
        // Sort by year (desc), month (desc), week (desc), day (asc)
        const sorted = [...data].sort((a, b) => {
            if (a.year !== b.year) return b.year - a.year;
            if (a.month !== b.month) return b.month - a.month;
            if (a.week !== b.week) return b.week - a.week;
            return a.day - b.day;
        });

        // Calculate daily summaries
        const dailySummaries: DailySummary[] = sorted.map((item) => ({
            year: item.year,
            month: item.month,
            monthName: item.month_name_ms,
            week: item.week,
            day: item.day,
            dayName: item.day_name_ms,
            dayTotal: parseFloat(item.day_total)
        }));
        setDailySummaries(dailySummaries);

        // Calculate weekly summaries
        const weeklyMap = new Map<string, WeeklySummary>();
        sorted.forEach((item) => {
            const key = `${item.year}-${item.month}-${item.week}`;
            if (!weeklyMap.has(key)) {
                weeklyMap.set(key, {
                    year: item.year,
                    month: item.month,
                    monthName: item.month_name_ms,
                    week: item.week,
                    weekTotal: 0
                });
            }
            weeklyMap.get(key)!.weekTotal += parseFloat(item.day_total);
        });
        setWeeklySummaries(Array.from(weeklyMap.values()));

        // Calculate monthly summaries
        const monthlyMap = new Map<string, MonthlySummary>();
        sorted.forEach((item) => {
            const key = `${item.year}-${item.month}`;
            if (!monthlyMap.has(key)) {
                monthlyMap.set(key, {
                    year: item.year,
                    month: item.month,
                    monthName: item.month_name_ms,
                    monthTotal: 0
                });
            }
            monthlyMap.get(key)!.monthTotal += parseFloat(item.day_total);
        });
        setMonthlySummaries(Array.from(monthlyMap.values()));

        // Calculate yearly summaries
        const yearlyMap = new Map<number, YearlySummary>();
        sorted.forEach((item) => {
            if (!yearlyMap.has(item.year)) {
                yearlyMap.set(item.year, {
                    year: item.year,
                    yearTotal: 0
                });
            }
            yearlyMap.get(item.year)!.yearTotal += parseFloat(item.day_total);
        });
        setYearlySummaries(Array.from(yearlyMap.values()));
    };

    // Helper function to destroy DataTable if exists
    const destroyDataTable = (tableRef: React.RefObject<HTMLTableElement | null>) => {
        if (!tableRef.current) return;

        const $ = (window as any).$;
        if (!$.fn.DataTable) return;

        const isDataTable = $(tableRef.current).hasClass('dataTable');
        if (isDataTable) {
            const table = $(tableRef.current).DataTable();
            table.destroy(true);
        }
    };

    // Handle tab switch - destroy DataTables and force re-render
    const handleTabSwitch = (tab: 'summary' | 'detailed') => {
        // Destroy all DataTables before switching
        destroyDataTable(dailyTableRef);
        destroyDataTable(weeklyTableRef);
        destroyDataTable(monthlyTableRef);
        destroyDataTable(yearlyTableRef);
        destroyDataTable(detailTableRef);

        setActiveTab(tab);
        setTabKey(prev => prev + 1);
    };

    // Initialize DataTables after tab switch
    useEffect(() => {
        if (!loading) {
            const timer = setTimeout(() => {
                const $ = (window as any).$;
                if (!$.fn.DataTable) return;

                if (activeTab === 'summary') {
                    // Initialize all four summary tables
                    [dailyTableRef, weeklyTableRef, monthlyTableRef, yearlyTableRef].forEach((ref) => {
                        if (ref.current && !$(ref.current).hasClass('dataTable')) {
                            $(ref.current).DataTable({
                                pageLength: 20,
                                lengthMenu: [10, 20, 50, 100],
                                language: {
                                    search: "Cari:",
                                    lengthMenu: "Tunjuk _MENU_ rekod setiap halaman",
                                    info: "Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod",
                                    infoEmpty: "Tiada rekod tersedia",
                                    infoFiltered: "(ditapis daripada _MAX_ rekod keseluruhan)",
                                    paginate: {
                                        first: "Pertama",
                                        last: "Terakhir",
                                        next: "Seterusnya",
                                        previous: "Sebelumnya"
                                    },
                                    zeroRecords: "Tiada rekod dijumpai"
                                },
                                order: [[0, 'desc']],
                                responsive: true
                            });
                        }
                    });
                } else if (activeTab === 'detailed') {
                    // Initialize detailed table
                    if (detailTableRef.current && !$(detailTableRef.current).hasClass('dataTable')) {
                        $(detailTableRef.current).DataTable({
                            pageLength: 20,
                            lengthMenu: [10, 20, 50, 100],
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tunjuk _MENU_ rekod setiap halaman",
                                info: "Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod",
                                infoEmpty: "Tiada rekod tersedia",
                                infoFiltered: "(ditapis daripada _MAX_ rekod keseluruhan)",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "Seterusnya",
                                    previous: "Sebelumnya"
                                },
                                zeroRecords: "Tiada rekod dijumpai"
                            },
                            order: [[7, 'desc'], [6, 'desc'], [5, 'desc'], [0, 'asc']],
                            responsive: true
                        });
                    }
                }
            }, 100);

            return () => clearTimeout(timer);
        }
    }, [loading, activeTab, tabKey]);

    // Cleanup all DataTables on unmount
    useEffect(() => {
        return () => {
            const $ = (window as any).$;
            [dailyTableRef, weeklyTableRef, monthlyTableRef, yearlyTableRef, detailTableRef].forEach(ref => {
                if (ref.current && $.fn.DataTable) {
                    const isDataTable = $(ref.current).hasClass('dataTable');
                    if (isDataTable) {
                        const table = $(ref.current).DataTable();
                        table.destroy(true);
                    }
                }
            });
        };
    }, []);

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
            /* Responsive DataTables */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            @media (max-width: 768px) {
                .table {
                    font-size: 12px;
                }
                .table th, .table td {
                    padding: 0.5rem 0.25rem;
                }
                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    display: block;
                    width: 100%;
                    margin-bottom: 0.5rem;
                }
                .dataTables_wrapper .dataTables_info {
                    font-size: 11px;
                }
                .dataTables_wrapper .dataTables_paginate {
                    font-size: 12px;
                }
            }
            /* Mobile-friendly tabs */
            @media (max-width: 768px) {
                .nav-tabs .nav-link {
                    padding: 0.5rem 0.75rem;
                    font-size: 14px;
                }
                .nav-tabs {
                    border-bottom: 1px solid #dee2e6;
                }
            }
        `}</style>
        <div className="row">
            <div className="col-12 col-lg-9">
                <h6 className="text mt-3 page-title-responsive"><b>Kutipan Tabung Masjid</b></h6>
                <p className="text mt-2 mb-3" style={{ fontSize: '14px' }}><i>Jumlah kutipan adalah di dalam Ringgit Malaysia (RM)</i></p>

                {/* Tab Buttons */}
                <div className="mb-3 d-flex flex-wrap gap-2">
                    <button
                        className={`btn ${activeTab === 'summary' ? 'btn-primary' : 'btn-secondary'}`}
                        onClick={() => handleTabSwitch('summary')}
                    >
                        Ringkasan Kutipan
                    </button>
                    <button
                        className={`btn ${activeTab === 'detailed' ? 'btn-primary' : 'btn-secondary'}`}
                        onClick={() => handleTabSwitch('detailed')}
                    >
                        Senarai Lengkap
                    </button>
                </div>

                <div key={tabKey} className="mt-3 mb-5 p-2 p-md-3 border rounded">
                    {loading ? (
                        <p>Memuat data kutipan...</p>
                    ) : activeTab === 'summary' ? (
                        <>
                            {/* Summary Table */}
                            <ul className="nav nav-tabs mb-3 flex-wrap" role="tablist">
                                <li className="nav-item" role="presentation">
                                    <a
                                        className="nav-link active"
                                        data-bs-toggle="tab"
                                        href="#daily"
                                        role="tab"
                                        aria-selected="true"
                                    >
                                        Harian
                                    </a>
                                </li>
                                <li className="nav-item" role="presentation">
                                    <a
                                        className="nav-link"
                                        data-bs-toggle="tab"
                                        href="#weekly"
                                        role="tab"
                                        aria-selected="false"
                                    >
                                        Mingguan
                                    </a>
                                </li>
                                <li className="nav-item" role="presentation">
                                    <a
                                        className="nav-link"
                                        data-bs-toggle="tab"
                                        href="#monthly"
                                        role="tab"
                                        aria-selected="false"
                                    >
                                        Bulanan
                                    </a>
                                </li>
                                <li className="nav-item" role="presentation">
                                    <a
                                        className="nav-link"
                                        data-bs-toggle="tab"
                                        href="#yearly"
                                        role="tab"
                                        aria-selected="false"
                                    >
                                        Tahunan
                                    </a>
                                </li>
                            </ul>

                            <div className="tab-content">
                                {/* Daily Summary */}
                                <div className="tab-pane fade show active" id="daily">
                                    <table
                                        ref={dailyTableRef}
                                        className="table table-striped table-bordered"
                                        style={{ width: '100%' }}
                                    >
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Minggu</th>
                                                <th>Hari</th>
                                                <th>Jumlah (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {dailySummaries.map((item, idx) => (
                                                <tr key={`day-${idx}`}>
                                                    <td>{item.year}</td>
                                                    <td>{item.monthName}</td>
                                                    <td>Minggu {item.week}</td>
                                                    <td>{item.dayName}</td>
                                                    <td>{item.dayTotal.toFixed(2)}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                {/* Weekly Summary */}
                                <div className="tab-pane fade" id="weekly">
                                    <table
                                        ref={weeklyTableRef}
                                        className="table table-striped table-bordered"
                                        style={{ width: '100%' }}
                                    >
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Minggu</th>
                                                <th>Jumlah (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {weeklySummaries.map((item, idx) => (
                                                <tr key={`week-${idx}`}>
                                                    <td>{item.year}</td>
                                                    <td>{item.monthName}</td>
                                                    <td>Minggu {item.week}</td>
                                                    <td>{item.weekTotal.toFixed(2)}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                {/* Monthly Summary */}
                                <div className="tab-pane fade" id="monthly">
                                    <table
                                        ref={monthlyTableRef}
                                        className="table table-striped table-bordered"
                                        style={{ width: '100%' }}
                                    >
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Jumlah (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {monthlySummaries.map((item, idx) => (
                                                <tr key={`month-${idx}`}>
                                                    <td>{item.year}</td>
                                                    <td>{item.monthName}</td>
                                                    <td>{item.monthTotal.toFixed(2)}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                {/* Yearly Summary */}
                                <div className="tab-pane fade" id="yearly">
                                    <table
                                        ref={yearlyTableRef}
                                        className="table table-striped table-bordered yearly-table"
                                        style={{ width: '100%' }}
                                    >
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Jumlah (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {yearlySummaries.map((item, idx) => (
                                                <tr key={`year-${idx}`}>
                                                    <td>{item.year}</td>
                                                    <td>{item.yearTotal.toFixed(2)}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </>
                    ) : (
                        /* Detailed Table */
                        <table
                            ref={detailTableRef}
                            className="table table-striped table-bordered"
                            style={{ width: '100%' }}
                        >
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jumlah Harian (RM)</th>
                                    <th>Jumlah Minggu (RM)</th>
                                    <th>Jumlah Bulan (RM)</th>
                                    <th>Jumlah Tahun (RM)</th>
                                    <th>Minggu</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                {kutipanList.map((item) => (
                                    <tr key={item.id}>
                                        <td>{item.day_name_ms}</td>
                                        <td>{item.day_total}</td>
                                        <td>{item.week_total}</td>
                                        <td>{item.month_total}</td>
                                        <td>{item.year_total}</td>
                                        <td>{item.week}</td>
                                        <td>{item.month_name_ms}</td>
                                        <td>{item.year}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>
            <div className="col-12 col-lg-3 mt-4 mt-lg-3">
                <div className="card">
                    <div className="card-body">
                        <h6 className="card-title page-title-responsive"><b>Jom Infaq</b></h6>
                        <p className="card-text" style={{ fontSize: '14px' }}><i>Sumbangan boleh dilakukan dengan mengimbas atau klik kod QR</i></p>
                        <div className="text-center mt-3">
                            <a href="https://infaqpay.my/go/masjidalmustaghfirinsungaitiram" target="_blank" rel="noopener noreferrer">
                                <img
                                    className="img-fluid"
                                    style={{ maxWidth: '100%', height: 'auto', maxHeight: '250px' }}
                                    src="/images/infaq_qr.png"
                                    alt="Infaq QR"
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
