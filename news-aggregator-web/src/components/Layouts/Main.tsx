import React from 'react';
import NavBar from './NavBar';
import AuthCheck from '../../context/AuthCheck';
import { Outlet } from 'react-router-dom';
import UserPreference from "../Prefrence/UserPreference";

const Main: React.FC = () => {
    return (
        <main className="main" data-ma-theme="green">
            <AuthCheck>
                <NavBar />
                <section className="content">
                    <UserPreference/>
                            <Outlet />
                            {/* Extra spacing could be handled with CSS instead */}
                            <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                </section>
            </AuthCheck>
        </main>
    );
};

export default Main;