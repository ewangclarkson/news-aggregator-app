import React from 'react';
import './404.css';
import StorageService from "../../services/StorageService";

const ErrorComponent: React.FC = () => {

    const isAuth = (): boolean => {
        return StorageService.isLoggedIn();
    }

    return (
        <section className="error">
            <div className="error__inner">
                <h1>404</h1>
                <h2>The page you are looking for doesn't exist!</h2>
                <p>
                    <a href={isAuth() ? "/home" : "/"}>Back To Home</a>
                </p>
            </div>
        </section>
    );
};

export default ErrorComponent;