// // src/index.jsx
// import React from 'react';
// import ReactDOM from 'react-dom/client';
// import { BrowserRouter, Routes, Route } from 'react-router-dom';
// import AdminApp from './admin/AdminApp';
// import './index.css'; // tailwind setup file
// <BrowserRouter>
//   <App />
// </BrowserRouter>
// ReactDOM.createRoot(document.getElementById('root')).render(
//   <React.StrictMode>
//     <BrowserRouter>
//       <Routes>
//         <Route path="/admin/*" element={<AdminApp />} />
//       </Routes>
//     </BrowserRouter>
//   </React.StrictMode>
// );
/////////////////////
// src/index.jsx
import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import AdminApp from './admin/AdminApp';
import './index.css';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>
      <Routes>
        <Route path="/admin/*" element={<AdminApp />} />
      </Routes>
    </BrowserRouter>
  </React.StrictMode>
);
