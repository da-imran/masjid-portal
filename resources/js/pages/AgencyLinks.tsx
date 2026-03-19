import React from 'react';
import { agencyLinks } from './data-sources/agencyLinks.ts';

const AgencyLinks: React.FC = () => {
    return (
        <div className="agency-links-section">
            <div className="row justify-content-center">
                <div className="col-12 col-md-8 col-lg-6">
                    <div
                        id="carouselAgencyLinks"
                        className="carousel slide"
                        data-bs-ride="carousel"
                        data-bs-interval="5000"
                    >
                        <div className="carousel-inner">
                            {agencyLinks.map((agency, index) => (
                                <div
                                    key={agency.id}
                                    className={`carousel-item ${index === 0 ? 'active' : ''} d-flex align-items-center justify-content-center`}
                                >
                                    <a
                                        href={agency.href}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="d-block"
                                    >
                                        <img
                                            src={agency.image}
                                            className="img-fluid d-block w-100"
                                            alt={agency.name}
                                            style={{
                                                maxHeight: '100px',
                                                objectFit: 'contain',
                                                backgroundColor: '#f8f9fa',
                                            }}
                                        />
                                    </a>
                                </div>
                            ))}
                        </div>

                        {agencyLinks.length > 1 && (
                            <>
                                <button
                                    className="carousel-control-prev"
                                    type="button"
                                    data-bs-target="#carouselAgencyLinks"
                                    data-bs-slide="prev"
                                >
                                    <span
                                        className="carousel-control-prev-icon"
                                        aria-hidden="true"
                                    />
                                    <span className="visually-hidden">Previous</span>
                                </button>
                                <button
                                    className="carousel-control-next"
                                    type="button"
                                    data-bs-target="#carouselAgencyLinks"
                                    data-bs-slide="next"
                                >
                                    <span
                                        className="carousel-control-next-icon"
                                        aria-hidden="true"
                                    />
                                    <span className="visually-hidden">Next</span>
                                </button>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AgencyLinks;
