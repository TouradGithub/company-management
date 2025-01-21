
<style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 20px;
      background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);
      min-height: 100vh;
      direction: rtl;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 10px;
      padding-top: 80px;
    }

    h1 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 40px;
      font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .profiles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      padding: 20px;
    }

    .profile-card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }

    .profile-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(45deg, #3498db, #2ecc71);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2em;
      font-weight: bold;
      margin-left: 15px;
    }

    .profile-name {
      flex-grow: 1;
    }

    .profile-name h2 {
      margin: 0;
      color: #2c3e50;
      font-size: 1.4em;
    }

    .profile-name p {
      margin: 5px 0 0;
      color: #7f8c8d;
    }

    .profile-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin: 20px 0;
      padding: 15px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
    }

    .stat {
      text-align: center;
    }

    .stat-value {
      font-size: 1.2em;
      font-weight: bold;
      color: #2c3e50;
    }

    .stat-label {
      font-size: 0.9em;
      color: #7f8c8d;
    }

    .profile-info {
      margin-top: 20px;
    }

    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .info-label {
      color: #7f8c8d;
      margin-left: 10px;
      font-weight: bold;
    }

    .info-value {
      color: #2c3e50;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      overflow-y: auto;
    }

    .modal-content {
      background: white;
      margin: 50px auto;
      padding: 20px;
      border-radius: 15px;
      max-width: 800px;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #7f8c8d;
    }

    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
    }

    .tab {
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .tab.active {
      background: #3498db;
      color: white;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .detail-section {
      margin-bottom: 20px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 10px;
    }

    .detail-title {
      font-weight: bold;
      margin-bottom: 10px;
      color: #2c3e50;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 8px;
      background: white;
      border-radius: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      text-align: right;
      border-bottom: 1px solid #eee;
    }

    th {
      background: #f8f9fa;
      font-weight: bold;
      color: #2c3e50;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 0.5s ease-out forwards;
    }

    .search-bar {
      max-width: 500px;
      margin: 0 auto 30px;
      position: relative;
    }

    .search-bar input {
      width: 100%;
      padding: 15px 20px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      font-size: 16px;
    }

    .search-bar input:focus {
      outline: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .search-filters {
      max-width: 500px;
      margin: 0 auto 20px;
      display: flex;
      gap: 10px;
    }

    .search-filtersForm {
      max-width: 500px;
      margin: 0 auto 20px;
      display: flex;
      gap: 10px;
    }

    .search-filters select {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      font-size: 16px;
      background: white;
    }

    .search-filtersForm select {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      font-size: 16px;
      background: white;
    }

    .search-filters select:focus {
      outline: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .search-filtersForm select:focus {
      outline: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .side-menu {
      display: none;
    }

    .header {
      background: linear-gradient(135deg, #3498db, #2ecc71);
      padding: 20px;
      color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 200;
    }

    .header-content {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-title {
      font-size: 1.8em;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .header-buttons {
      display: flex;
      gap: 15px;
    }
.search-bar button {
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 12px 24px; /* Add some padding */
    border: none; /* Remove border */
    border-radius: 4px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}
    .header-btn {
      padding: 10px 20px;
      border: 2px solid white;
      border-radius: 8px;
      background: transparent;
      color: white;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .header-btn:hover {
      background: white;
      color: #3498db;
    }
 </style>
