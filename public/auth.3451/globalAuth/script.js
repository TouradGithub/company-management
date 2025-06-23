// Bubble animation
function createBubble() {
  const bubble = document.createElement('div');
  bubble.className = 'bubble';
  
  // Random properties
  const size = Math.random() * 60 + 20;
  const left = Math.random() * 100;
  const riseDuration = Math.random() * 4 + 4;
  const swayDuration = Math.random() * 2 + 2;
  const swayAmount = (Math.random() - 0.5) * 200;
  
  bubble.style.width = `${size}px`;
  bubble.style.height = `${size}px`;
  bubble.style.left = `${left}%`;
  bubble.style.setProperty('--rise-duration', `${riseDuration}s`);
  bubble.style.setProperty('--sway-duration', `${swayDuration}s`);
  bubble.style.setProperty('--sway-amount', `${swayAmount}px`);
  
  document.body.appendChild(bubble);
  
  // Remove bubble after animation
  setTimeout(() => {
    bubble.remove();
  }, riseDuration * 1000);
}

// Create bubbles periodically
setInterval(createBubble, 300);

function handleLogin(event) {
  event.preventDefault();
  
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const userType = document.getElementById('userType').value;
  
  // Basic validation
  if (username && password && userType) {
    // Display success message with user type
    alert(`تم تسجيل الدخول بنجاح!\nنوع المستخدم: ${
      userType === 'admin' ? 'مدير النظام' :
      userType === 'company' ? 'مدير الشركة' :
      'مدير الفرع'
    }`);
  }
  
  return false;
}

// Button hover effects
document.querySelectorAll('.login-btn, .links a').forEach(element => {
  element.addEventListener('mouseenter', e => {
    e.target.style.transform = 'scale(1.05)';
  });
  
  element.addEventListener('mouseleave', e => {
    e.target.style.transform = 'scale(1)';
  });
});

// Update date display
function updateDateTime() {
  const dateDisplay = document.getElementById('dateDisplay');
  if (dateDisplay) {
    // Get current Gregorian date formatted in Arabic
    const currentGregorian = new Date().toLocaleDateString('ar-SA', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      weekday: 'long',
      calendar: 'gregory', 
      numberingSystem: 'arab'
    });
    
    dateDisplay.innerHTML = `
      <div class="date-row">
        <span class="date-value">${currentGregorian}</span>
      </div>
    `;
  }
}

// Add mouse light effect
document.addEventListener('DOMContentLoaded', () => {
  updateDateTime();
  setInterval(updateDateTime, 60000);
  
  const mouseLight = document.createElement('div');
  mouseLight.className = 'mouse-light';
  document.body.appendChild(mouseLight);

  const colorRing = document.createElement('div');
  colorRing.className = 'color-ring';
  document.body.appendChild(colorRing);

  let isMoving = false;
  let moveTimeout;
  let lastX = 0;
  let lastY = 0;

  document.addEventListener('mousemove', (e) => {
    const dx = e.clientX - lastX;
    const dy = e.clientY - lastY;
    const speed = Math.sqrt(dx * dx + dy * dy);
    
    const opacity = Math.min(speed / 100, 1);
    
    mouseLight.style.left = e.clientX + 'px';
    mouseLight.style.top = e.clientY + 'px';
    colorRing.style.left = e.clientX + 'px';
    colorRing.style.top = e.clientY + 'px';

    mouseLight.style.opacity = opacity;
    colorRing.style.opacity = Math.min(0.7 + (speed / 200), 1);
    
    if (speed > 50) {
      colorRing.style.width = '200px';
      colorRing.style.height = '200px';
    } else {
      colorRing.style.width = '150px';
      colorRing.style.height = '150px';
    }

    lastX = e.clientX;
    lastY = e.clientY;

    if (!isMoving) {
      isMoving = true;
      mouseLight.style.opacity = '1';
    }

    clearTimeout(moveTimeout);
    moveTimeout = setTimeout(() => {
      isMoving = false;
      mouseLight.style.opacity = '0';
      colorRing.style.width = '150px';
      colorRing.style.height = '150px';
    }, 100);
  });

  document.addEventListener('mouseenter', () => {
    mouseLight.style.opacity = '1';
  });

  document.addEventListener('mouseleave', () => {
    mouseLight.style.opacity = '0';
  });
});