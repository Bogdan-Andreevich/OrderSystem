<template>
    <div :style="{ paddingLeft: sectionData.index * 20 + 'px' }" class="que-template">
        <button class="btn btn-info btn-sm" @click="toggleVisibility(sectionData)">+</button>
        <span style="margin-left: 20px;"> {{ sectionData.question }}</span>
        <div v-if="sectionData.isVisible">
            <button class="btn btn-danger btn-sm" @click="$emit('deleteQuestion', sectionData.id)">X</button><br />
            <label for="input-1" class="form-label">Питання:</label>
            <input type="text" id="input-1" class="form-control" v-model="sectionData.question">
            <label for="input-2" class="form-label">Питання для опису:</label>
            <input type="text" id="input-2" class="form-control" v-model="sectionData.question_description">
            <div class="input-container">
                <label for="text-input" class="form-label">Додавати до опису:</label>
                <input type="checkbox" id="is_add_description" v-model="sectionData.is_add_description"
                    :checked="sectionData.is_add_description == 1">
                <section v-if="sectionData.index !== 0">
                    <label for="checkbox" class="form-label">Відображати, коли: </label>
                    <select id="selector" class="form-select" v-model="sectionData.selectedOption">
                        <option v-for="(answer, answerIndex) in answers" :key="answerIndex" :value="answer.title">
                            {{ answer.title }}
                        </option>
                    </select>
                </section>

            </div>
            <div class="question-navigation">
                <button class="btn btn-primary" @click="addAnswers(sectionData, sectionData.index)">Додати
                    відповідь</button>
                <button class="btn btn-secondary" @click="addSubQuestion(sectionData)">Додати субпитання</button>
            </div>
            <div class="answer" v-for="(answer, answerIndex) in sectionData.answers" :key="answerIndex">
                <button class="btn btn-tool" @click="removeAnswer(sectionIndex, answerIndex)">
                    <i class="fas fa-times"></i>
                </button>
                <label for="text-input" class="form-label">Відповідь:</label>
                <input type="text" id="text-input" class="form-control" v-model="answer.title">

                <label for="text-input" class="form-label">Відповідь до опису:</label>
                <input type="text" id="text-input" class="form-control" v-model="answer.description">

                <label for="text-input" class="form-label">Коментар:</label>
                <input type="text" id="text-input" class="form-control" v-model="answer.comments">

                <label for="selector" class="form-label">Додати до ціни:</label>
                <select id="selector" class="form-select" v-model="answer.addToPrice">
                    <option v-for="(price, priceIndex) in priceList" :key="priceIndex" :value="price.name">
                        {{ price.name }} - {{ price.price }}
                    </option>
                </select>
            </div>
            <div v-if="sectionData.subQuestions.length > 0">
            <draggable :list="sectionData.subQuestions" group="actions" @start="dragStart" @end="dragEnd" item-key="index">
                <template #item="{ element, index }">
                    <div> 
                        <question-section 
                            :priceList="priceList" 
                            :answers="sectionData.answers" 
                            :sectionData="element"  
                            :index="sectionData.index" 
                            @deleteQuestion="handleDeleteSubQuestion(index, element.id)"
                            @add-sub-question="addSubQuestion"
                        >   
                        </question-section>
                    </div>
                </template>
            </draggable>
</div>


        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    name: "QuestionSection",
    components: {
        draggable,
    },
    props: ['sectionData', 'index', 'priceList', 'answers'],
    methods: {
        toggleVisibility(sectionData) {
            if (typeof sectionData.isVisible === 'undefined') {
                sectionData.isVisible = true; // This will be reactive in Vue 3
            } else {
                sectionData.isVisible = !sectionData.isVisible;
            }
        },
        addSubQuestion(parentQuestion) {
            const newSubQuestion = {
                id: Date.now(),
                question: "",
                index: parentQuestion.index + 1,
                question_description: "",
                subQuestions: [],
                answers: []
            };
            parentQuestion.subQuestions.push(newSubQuestion);
        },
        addAnswers(parentQuestion) {

            parentQuestion.answers.push({
                title: '', /* ... other answer fields */
                description: '', /* ... other answer fields */
                comments: '', /* ... other answer fields */
                addToPrice: '', /* ... other answer fields */
            });
        },
        removeAnswer(sectionIndex, answerIndex) {
            this.sectionData.answers.splice(answerIndex, 1);
        },
        handleDeleteSubQuestion(sectionIndex, questionId) {
            const section = this.sectionData;
            if (section) {
                const subQuestionIndex = section.subQuestions.findIndex(subQ => +subQ.id === +questionId);
                if (subQuestionIndex !== -1) {
                    section.subQuestions.splice(subQuestionIndex, 1);
                }
            }
        }

    },
}
</script>

<style scoped>
.que-template {
    margin-bottom: 20px;
}

.input-container {
    margin-top: 20px;
}

.answer {
    border-bottom: 1px solid grey;
}
</style>