import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '@/lib/api';
import type { NewsItem } from '@/types';

const BeritaDetailPage: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const [berita, setBerita] = useState<NewsItem | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchBerita = async () => {
            if (!id) return;

            try {
                const response = await api.get<NewsItem>(`/api/berita/${id}`);
                setBerita(response.data);
            } catch (err) {
                console.error('Failed to fetch berita:', err);
                setError('Gagal memuat berita');
            } finally {
                setLoading(false);
            }
        };

        fetchBerita();
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
                <p>Memuat berita...</p>
            </div>
        );
    }

    if (error || !berita) {
        return (
            <div className="container">
                <p className="text-danger">{error || 'Berita tidak ditemui'}</p>
                <Link to="/information/berita" className="btn btn-secondary">
                    Kembali ke Senarai Berita
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
                            <li className="breadcrumb-item"><Link to="/information/berita">Berita Semasa</Link></li>
                            <li className="breadcrumb-item active" aria-current="page">{berita.title_ms}</li>
                        </ol>
                    </nav>

                    <div className="card">
                        <div className="card-body">
                            <h1 className="card-title">{berita.title_ms}</h1>
                            <p className="text-muted">
                                <small>Dikemaskinikan pada {formatDate(berita.updated_at)}</small>
                            </p>
                            <hr />

                            {berita.image_name && (
                                <div className="text-center mb-4">
                                    <img
                                        src={`/storage/${berita.image_name}`}
                                        alt={berita.title_ms}
                                        className="img-fluid"
                                        style={{ maxHeight: '400px' }}
                                    />
                                </div>
                            )}

                            <div className="card-text">
                                {berita.content_ms ? (
                                    <div dangerouslySetInnerHTML={{ __html: berita.content_ms }} />
                                ) : (
                                    <p>{stripHtml(berita.description_ms)}</p>
                                )}
                            </div>

                            <hr />
                            <Link to="/information/berita" className="btn btn-secondary">
                                &larr; Kembali ke Senarai Berita
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default BeritaDetailPage;
