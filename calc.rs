#[allow(dead_code)]
#[derive(Clone, Copy, Debug)]
struct Count {
    lines_in_total: usize,
    lines_containing_code: usize,
    empty_lines: usize,
    code_symbols: usize,
}

impl Count {
    #[allow(dead_code)]
    pub fn calculate(text: &str) -> Count {
        let mut count = Count {
            lines_in_total: 0,
            lines_containing_code: 0,
            empty_lines: 0,
            code_symbols: 0,
        };

        // Используем итератор для обработки каждой строки
        for line in text.lines() {
            count.lines_in_total += 1;

            // Проверяем, является ли строка пустой
            if line.trim().is_empty() {
                count.empty_lines += 1;
            } else {
                count.lines_containing_code += 1;
                // Добавляем количество символов в строке, включая пробелы и символ перевода строки
                // За исключением последнего символа перевода строки в тексте
                count.code_symbols += line.len() + 1; // Добавляем 1 для символа перевода строки
            }
        }

        // Убираем последний символ перевода строки из подсчета, если текст не пустой
        if !text.is_empty() {
            count.code_symbols -= 1;
        }

        count
    }
}
