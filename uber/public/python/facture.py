import sys
import json

def translate_invoice(data, language):
    # Simulation d'une traduction (utilisez une API si nécessaire)
    translations = {
        "en": "Invoice",
        "fr": "Facture",
        "es": "Factura"
    }
    translated_invoice = {
        "title": translations.get(language, "Invoice"),
        "details": data
    }
    return translated_invoice

if __name__ == "__main__":
    # Lire les données de l'entrée standard
    input_data = json.loads(sys.stdin.read())
    language = input_data.get("language", "en")
    invoice_data = input_data.get("invoice_data", {})

    # Générer la facture traduite
    result = translate_invoice(invoice_data, language)

    # Renvoyer le résultat sous forme de JSON
    print(json.dumps(result))


from googletrans import Translator

def translate_text(text, dest_language):
    translator = Translator()
    return translator.translate(text, dest=dest_language).text
