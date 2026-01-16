import React from 'react';

const ContactPage: React.FC = () => {
    return (
        <div className="row">
            <div className="col-md-12">
                <h6 className="text fs-5 mb-4"><b>Hubungi Kami</b></h6>
                <div className="row">
                    <div className="col-md-6">
                        <p className="fs-4 mb-1">Alamat</p>
                        <p className="fs-6">Masjid Al Mustaghfirin, Bayan Lepas</p>
                        <p className="fs-6">Jalan Sultan Azlan Shah, Kampung Sungai Tiram,</p>
                        <p className="fs-6">11900 Bayan Lepas,</p>
                        <p className="fs-6">Pulau Pinang</p>
                        <div className="mt-3">
                            <p className="fs-4 mb-1">Telefon</p>
                            <p className="fs-6">+6019-320-0799</p>
                        </div>
                    </div>
                    <div className="col-md-3 text-center">
                        <h6 className="text fs-5 mb-3"><b>Layari Facebook</b></h6>
                        <div className="d-flex justify-content-center">
                            <a href="https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram/" target="_blank" rel="noopener noreferrer">
                                <img className="img-fluid" style={{ width: '200px', height: '200px' }} src="/images/fb_qr.png" alt="Facebook QR" />
                            </a>
                        </div>
                    </div>
                    <div className="col-md-3 text-center">
                        <h6 className="text fs-5 mb-3"><b>Lokasi Masjid</b></h6>
                        <div className="d-flex justify-content-center">
                            <a href="https://maps.app.goo.gl/6Ho4nqzHfxZENYYLA" target="_blank" rel="noopener noreferrer">
                                <img className="img-fluid" style={{ width: '200px', height: '200px' }} src="/images/map_qr.png" alt="Map QR" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <style>{`
                .content-wrapper {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
            `}</style>
        </div>
    );
};

export default ContactPage;
