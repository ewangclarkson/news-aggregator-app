import React from 'react';
import {User} from '../../models/User';
import icon from "../Assets/innoscripta-logo.svg";
import profile from "../Assets/profile.jpg";
import {useAuth} from "../../context/AuthContext";
import StorageService from "../../services/StorageService";

const NavBar: React.FC = () => {
    const user: User | null = StorageService.getCurrentUser();
    const checkIn = useAuth();

    const logout = async () => {
        await checkIn.logout();
    };

    return (
        <header className="header">
            <div className="header__logo">
                <img src={icon} width="150"/>
            </div>

            <form className="search">
                <div className="search__inner">
                    <input type="text" className="search__text" placeholder=""/>
                </div>
            </form>
            <div className="dropdown">
                <a href="" data-toggle="dropdown">
                    <div className="user__info">
                        <img className="user__img" src={profile} alt=""/>
                        <div>
                            <div className="user__name">{user?.name}</div>
                            <div className="user__email text-white">{user?.email}</div>
                        </div>
                    </div>
                </a>
                <div className="dropdown-menu ">
                    <div className="listview listview--hover">
                        <div className="listview__header">
                            <div className="user__info">
                                <img className="user__img" src={profile} alt=""/>
                                <div>
                                    <div className="user__name text-left">{user?.name}</div>
                                    <div className="user__email">{user?.email}</div>
                                </div>
                            </div>
                        </div>

                        <a className="listview__item" data-toggle="modal"  data-target="#modal-small">
                            <div className="listview__content">
                                <div className="listview__heading">
                                    User preference
                                </div>
                            </div>
                        </a>
                        <a className="listview__item" href="#" onClick={logout}>
                            <div className="listview__content">
                                <div className="listview__heading">
                                    Logout
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </header>
    );
};

export default NavBar;