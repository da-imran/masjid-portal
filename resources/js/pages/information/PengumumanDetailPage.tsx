import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '@/lib/api';
import type { Announcement } from '@/types';

const PengumumanDetailPage: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const [pengumuman, setPengumuman] = useState<Announcement | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchPengumuman = async () => {
            if (!id) return;

            try {
                const response = await api.get<Announcement>(`/api/pengumuman/${id}`);
                setPengumuman(response.data);
            } catch (err) {
                console.error('Failed to fetch pengumuman:', err);
                setError('Gagal memuat pengumuman');
            } finally {
                setLoading(false);
            }
        };

        fetchPengumuman();
    }, [id]);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'long', year: 'numeric' });
    };

    const stripHtml = (html: string): string => {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    };

    if (loading) {
        return (
            <div className="container">
                <p>Memuat pengumuman...</p>
            </div>
        );
    }

    if (error || !pengumuman) {
        return (
            <div className="container">
                <p className="text-danger">{error || 'Pengumuman tidak ditemui'}</p>
                <Link to="/information/pengumuman" className="btn btn-secondary">
                    Kembali ke Senarai Pengumuman
                </Link>
            </div>
        );
    }

    return (
        <div className="container">
            <div className="row">
                <div className="col-md-10">
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb">
                            <li className="breadcrumb-item"><Link to="/">Utama</Link></li>
                            <li className="breadcrumb-item"><Link to="/information/pengumuman">Pengumuman</Link></li>
                            <li className="breadcrumb-item active" aria-current="page">{pengumuman.title_ms}</li>
                        </ol>
                    </nav>

                    <div className="card">
                        <div className="card-body">
                            <h1 className="card-title">{pengumuman.title_ms}</h1>
                            <p className="text-muted">
                                <small>Dikemaskinikan pada {formatDate(pengumuman.updated_at)}</small>
                            </p>
                            <hr />

                            {pengumuman.image_name && (
                                <div className="text-center mb-4">
                                    <img
                                        src={`/storage/${pengumuman.image_name}`}
                                        alt={pengumuman.title_ms}
                                        className="img-fluid"
                                        style={{ maxHeight: '400px' }}
                                    />
                                </div>
                            )}

                            <div className="card-text">
                                {pengumuman.content_ms ? (
                                    <div dangerouslySetInnerHTML={{ __html: pengumuman.content_ms }} />
                                ) : (
                                    <p>{stripHtml(pengumuman.description_ms)}</p>
                                )}
                            </div>

                            <hr />
                            <Link to="/information/pengumuman" className="btn btn-secondary">
                                &larr; Kembali ke Senarai Pengumuman
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PengumumanDetailPage;
