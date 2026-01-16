import React, { useState, useEffect } from 'react';
import api from '@/lib/api';
import type { DownloadItem } from '@/types';

const JadualKuliahPage: React.FC = () => {
    const [downloadList, setDownloadList] = useState<DownloadItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDownloads = async () => {
            try {
                const response = await api.get<DownloadItem[]>('/api/v1/downloads/jadual_kuliah');
                // Ensure response.data is an array
                const data = Array.isArray(response.data) ? response.data : [];
                setDownloadList(data);
            } catch (err) {
                console.error('Failed to fetch jadual kuliah:', err);
                setDownloadList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchDownloads();
    }, []);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
    };

    const formatFileSize = (bytes?: number): string => {
        if (!bytes) return '-';
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        return `${size.toFixed(1)} ${units[unitIndex]}`;
    };

    const handleDownload = async (item: DownloadItem) => {
        try {
            // Increment download count
            await api.post(`/api/v1/downloads/item/${item.id}`);
            // Trigger file download
            window.open(item.file_path, '_blank');
        } catch (err) {
            console.error('Failed to download file:', err);
            // Still open the file even if count increment fails
            window.open(item.file_path, '_blank');
        }
    };

    return (
        <div className="row">
            <div className="col-md-10">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Jadual Kuliah</b></h6>
                <div className="mt-5 mb-5 p-3 border">
                    {loading ? (
                        <p>Memuat jadual kuliah...</p>
                    ) : downloadList.length > 0 ? (
                        <table className="table table-striped" style={{ width: '100%' }}>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Saiz</th>
                                    <th>Muat Turun</th>
                                    <th>Tarikh Dicipta</th>
                                </tr>
                            </thead>
                            <tbody>
                                {downloadList.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{index + 1}</td>
                                        <td>
                                            <div>
                                                <strong>{item.title_ms}</strong>
                                                {item.description_ms && (
                                                    <small className="d-block text-muted">
                                                        {item.description_ms.substring(0, 100)}
                                                        {item.description_ms.length > 100 ? '...' : ''}
                                                    </small>
                                                )}
                                            </div>
                                        </td>
                                        <td>{formatFileSize(item.file_size)}</td>
                                        <td>
                                            <button
                                                className="btn btn-sm btn-primary"
                                                onClick={() => handleDownload(item)}
                                            >
                                                Muat Turun
                                            </button>
                                        </td>
                                        <td>{formatDate(item.created_at)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>Tiada jadual kuliah buat masa ini.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default JadualKuliahPage;
