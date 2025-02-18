import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import CheckIn from "./components/Auth/CheckIn";
import Main from "./components/Layouts/Main";
import ErrorComponent from "./components/Alert/404";
import NewsFeed from "./components/Feed/NewsFeed"; // Ensure the correct path

const App: React.FC = () => {
    return (
        <BrowserRouter>
            <AuthProvider>
                <Routes>
                    <Route path="/" element={<CheckIn />} />
                    <Route path="/home" element={<Main />}>
                        <Route index element={<NewsFeed />} />
                    </Route>
                    <Route path="*" element={<ErrorComponent />} />
                </Routes>
            </AuthProvider>
        </BrowserRouter>
    );
};

export default App;