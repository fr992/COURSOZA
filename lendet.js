const subjects = document.querySelectorAll('.subject');
subjects.forEach(subject => {
    subject.addEventListener('mouseover', () => {
        subject.style.backgroundColor = '#d4a3ff';
        subject.style.cursor = 'pointer';
    });
    subject.addEventListener('mouseout', () => {
        subject.style.backgroundColor = '';
    });
    subject.addEventListener('click', () => {
        alert(`You selected: ${subject.textContent}`);
    });
});