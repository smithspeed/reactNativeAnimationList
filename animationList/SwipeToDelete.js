import React, { useCallback, useState } from 'react'
import { View, Text, StyleSheet, StatusBar, SafeAreaView, ScrollView } from 'react-native'
import ListItem from '../components/ListItem';

const TITLE = [
    'Record the dismissible tutorial',
    'Leave to the video',
    'Check Youtube Comments',
    'Subscribe to the channel',
    'Leave a on the Github Repo',
]

const TASKS = TITLE.map((title,index) => ({title,index}));

const BACKGROUND_COLOR = '#FAFBFF';

export default function SwipeToDelete() {

    const [tasks, setTasks] = useState(TASKS);

    const onDismiss = useCallback((task) => {
        setTasks((tasks) => tasks.filter((item) => item.index !== task.index ))
    },[])

    return (
        <SafeAreaView style={styles.container}>
            <StatusBar backgroundColor={BACKGROUND_COLOR} />
            <Text style={styles.title}>
                Tasks
            </Text>
            <ScrollView style={{flex : 1}}>
                {
                    tasks.map((task) => (
                        <ListItem key={task.index} task={task} onDismiss={onDismiss} />
                    ))
                }
            </ScrollView>
        </SafeAreaView>
    )
}

const styles = StyleSheet.create({

    container : {
        flex : 1,
        backgroundColor : BACKGROUND_COLOR,
    },
    title : {
        fontSize : 60,
        marginVertical : 20,
        paddingLeft : '5%',
    }
});