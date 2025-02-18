import React from 'react';
import './Loader.css';

interface LoaderProps {
    className?: string;
}

const Loader: React.FC<LoaderProps> = ({ className }) => {
    return (
        <div className={className}>
            <div className="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" strokeWidth="2" strokeMiterlimit="10" />
                </svg>
            </div>
        </div>
    );
};

export default Loader;